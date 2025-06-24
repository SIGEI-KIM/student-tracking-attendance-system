<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Make sure this is imported

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::latest()->paginate(10);
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        // Validate only what the user provides
        $request->validate([
            'course_type' => 'required|string|max:255',
            'name' => 'required|string|max:255|unique:courses,name',
            // 'abbreviation' is generated, so it's NOT in the validation rules here
        ]);

        $data = $request->all();

        // Generate abbreviation from the provided name
        $generatedAbbreviation = $this->generateAbbreviation($data['name']);

        // Check if the generated abbreviation already exists
        // If it does, we need to make it unique (e.g., by adding a number)
        $originalAbbreviation = $generatedAbbreviation;
        $counter = 1;
        while (Course::where('abbreviation', $generatedAbbreviation)->exists()) {
            $generatedAbbreviation = $originalAbbreviation . $counter++;
            // To prevent excessively long abbreviations, you might want to truncate originalAbbreviation
            // e.g., if originalAbbreviation is "INFOTECH" and counter is "10", it becomes "INFOTECH10"
            // You might do: $generatedAbbreviation = Str::limit($originalAbbreviation, 18, '') . $counter++;
        }

        $data['abbreviation'] = $generatedAbbreviation;

        Course::create($data);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course created successfully.');
    }

    public function show(Course $course)
    {
        return view('admin.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        // Validate only what the user provides
        $request->validate([
            'course_type' => 'required|string|max:255',
            'name' => 'required|string|max:255|unique:courses,name,' . $course->id,
            // 'abbreviation' is generated, so it's NOT in the validation rules here
        ]);

        $data = $request->all();
        $regenerateAbbreviation = false;

        // Only regenerate abbreviation if the name has changed
        if ($course->name !== $data['name']) {
            $regenerateAbbreviation = true;
        }

        if ($regenerateAbbreviation) {
            $generatedAbbreviation = $this->generateAbbreviation($data['name']);

            // Handle uniqueness for updated abbreviation
            $originalAbbreviation = $generatedAbbreviation;
            $counter = 1;
            while (Course::where('abbreviation', $generatedAbbreviation)
                          ->where('id', '!=', $course->id) // Exclude current course from unique check
                          ->exists()) {
                $generatedAbbreviation = $originalAbbreviation . $counter++;
            }
            $data['abbreviation'] = $generatedAbbreviation;
        }

        $course->update($data);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    /**
     * Generates an abbreviation from a given string.
     * Example: "Information Technology" -> "IT"
     * "Bachelor of Science in Computer Science" -> "BSCS" or "BS-CS"
     * "Diploma in Business Management" -> "DBM"
     */
    protected function generateAbbreviation(string $name): string
    {
        // Convert to title case for consistency (e.g., "information technology" -> "Information Technology")
        $name = Str::title($name);

        // Define common words to ignore
        $stopWords = ['of', 'and', 'the', 'for', 'in', 'on', 'with', 'a', 'an', 'is', 'at', 'by', 'to'];

        // Split the name into words, handle hyphens if necessary (e.g., "full-stack")
        $words = preg_split('/[\s,\-]+/', $name, -1, PREG_SPLIT_NO_EMPTY);

        $abbreviation = '';
        foreach ($words as $word) {
            $cleanedWord = Str::lower($word); // For stop word comparison

            // If it's a stop word, skip it
            if (in_array($cleanedWord, $stopWords)) {
                continue;
            }

            // If the word has 3 or more characters, take the first letter
            if (Str::length($word) >= 3) {
                $abbreviation .= Str::upper(Str::substr($word, 0, 1));
            } else {
                // For very short words (1-2 chars) not in stop words, use the whole word
                // This handles things like "IT" if "Information Technology" was input
                $abbreviation .= Str::upper($word);
            }
        }

        // Fallback if abbreviation is empty (e.g., name was only stop words or very short)
        if (empty($abbreviation)) {
            // Take first letter of each word, even stop words, if no abbreviation generated
            foreach ($words as $word) {
                $abbreviation .= Str::upper(Str::substr($word, 0, 1));
            }
        }

        // Ensure abbreviation is not too long (match your DB column size, e.g., 20)
        $abbreviation = Str::limit($abbreviation, 20, '');

        // If it's still empty, use a slugged version
        if (empty($abbreviation)) {
            $abbreviation = Str::upper(Str::slug($name, '')); // "information-technology" -> "INFORMATIONTECHNOLOGY"
            $abbreviation = Str::limit($abbreviation, 20, '');
        }

        return $abbreviation;
    }
}