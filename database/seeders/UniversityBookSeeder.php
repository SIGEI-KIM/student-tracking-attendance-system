<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book; // Make sure to import your Book model

class UniversityBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            // Computer Science / IT
            [
                'title' => 'Introduction to Algorithms',
                'author' => 'Thomas H. Cormen, Charles E. Leiserson, Ronald L. Rivest, Clifford Stein',
                'isbn' => '978-0262033848',
                'publisher' => 'MIT Press',
                'publication_year' => 2009,
                'description' => 'A comprehensive textbook on computer algorithms.',
                'cover_image_url' => 'https://images-na.ssl-images-amazon.com/images/I/41T2Ym180dL._SX390_BO1,204,203,200_.jpg',
                'total_copies' => 7,
                'available_copies' => 7,
            ],
            [
                'title' => 'Operating System Concepts',
                'author' => 'Abraham Silberschatz, Peter B. Galvin, Greg Gagne',
                'isbn' => '978-1118063330',
                'publisher' => 'Wiley',
                'publication_year' => 2012,
                'description' => 'A classic textbook on operating systems.',
                'cover_image_url' => 'https://images-na.ssl-images-amazon.com/images/I/41bQ1m9XFGL._SY445_SX342_BO1,204,203,200_.jpg',
                'total_copies' => 5,
                'available_copies' => 5,
            ],
            [
                'title' => 'Database System Concepts',
                'author' => 'Abraham Silberschatz, Henry F. Korth, S. Sudarshan',
                'isbn' => '978-0073523323',
                'publisher' => 'McGraw-Hill Education',
                'publication_year' => 2010,
                'description' => 'Comprehensive coverage of database systems.',
                'cover_image_url' => 'https://images-na.ssl-images-amazon.com/images/I/51wXh-o952L._SX367_BO1,204,203,200_.jpg',
                'total_copies' => 6,
                'available_copies' => 6,
            ],
            [
                'title' => 'Computer Networking: A Top-Down Approach',
                'author' => 'James F. Kurose, Keith W. Ross',
                'isbn' => '978-0132856201',
                'publisher' => 'Pearson',
                'publication_year' => 2012,
                'description' => 'An introduction to computer networking principles.',
                'cover_image_url' => 'https://images-na.ssl-images-amazon.com/images/I/41+9bY2wTPL._SX357_BO1,204,203,200_.jpg',
                'total_copies' => 4,
                'available_copies' => 4,
            ],
            [
                'title' => 'Clean Code: A Handbook of Agile Software Craftsmanship',
                'author' => 'Robert C. Martin',
                'isbn' => '978-0132350884',
                'publisher' => 'Prentice Hall',
                'publication_year' => 2008,
                'description' => 'Essential guidelines for writing clean and maintainable code.',
                'cover_image_url' => 'https://images-na.ssl-images-amazon.com/images/I/41z96GkGvFL._SX322_BO1,204,203,200_.jpg',
                'total_copies' => 3,
                'available_copies' => 2, // One copy currently borrowed
            ],

            // Business / Economics
            [
                'title' => 'Principles of Microeconomics',
                'author' => 'N. Gregory Mankiw',
                'isbn' => '978-1305971493',
                'publisher' => 'Cengage Learning',
                'publication_year' => 2017,
                'description' => 'An introductory textbook on the principles of microeconomics.',
                'cover_image_url' => 'https://images-na.ssl-images-amazon.com/images/I/41V-rM-pCML._SX385_BO1,204,203,200_.jpg',
                'total_copies' => 8,
                'available_copies' => 8,
            ],
            [
                'title' => 'Marketing Management',
                'author' => 'Philip Kotler, Kevin Lane Keller',
                'isbn' => '978-0133856460',
                'publisher' => 'Pearson',
                'publication_year' => 2015,
                'description' => 'A leading textbook in marketing management.',
                'cover_image_url' => 'https://images-na.ssl-images-amazon.com/images/I/41-lP6pDqXL._SX379_BO1,204,203,200_.jpg',
                'total_copies' => 5,
                'available_copies' => 5,
            ],

            // Science
            [
                'title' => 'Campbell Biology',
                'author' => 'Lisa A. Urry, Michael L. Cain, Steven A. Wasserman, Peter V. Minorsky, Jane B. Reece',
                'isbn' => '978-0321962238',
                'publisher' => 'Pearson',
                'publication_year' => 2016,
                'description' => 'A widely used biology textbook for university students.',
                'cover_image_url' => 'https://images-na.ssl-images-amazon.com/images/I/51wXh-o952L._SX367_BO1,204,203,200_.jpg', // Placeholder, find real one
                'total_copies' => 10,
                'available_copies' => 10,
            ],
            [
                'title' => 'Principles of Physics',
                'author' => 'David Halliday, Robert Resnick, Jearl Walker',
                'isbn' => '978-1118230718',
                'publisher' => 'Wiley',
                'publication_year' => 2013,
                'description' => 'A popular calculus-based physics textbook.',
                'cover_image_url' => 'https://images-na.ssl-images-amazon.com/images/I/41Xl31i-HLL._SX384_BO1,204,203,200_.jpg', // Placeholder
                'total_copies' => 6,
                'available_copies' => 6,
            ],

            // Humanities / Social Sciences
            [
                'title' => 'The Norton Anthology of English Literature',
                'author' => 'Stephen Greenblatt et al.',
                'isbn' => '978-0393603132',
                'publisher' => 'W. W. Norton & Company',
                'publication_year' => 2018,
                'description' => 'A comprehensive collection of English literary works.',
                'cover_image_url' => 'https://images-na.ssl-images-amazon.com/images/I/51Qd6m5e3oL._SX342_BO1,204,203,200_.jpg',
                'total_copies' => 4,
                'available_copies' => 4,
            ],
            [
                'title' => 'Sapiens: A Brief History of Humankind',
                'author' => 'Yuval Noah Harari',
                'isbn' => '978-0062316097',
                'publisher' => 'Harper',
                'publication_year' => 2014,
                'description' => 'A global bestseller exploring the history of Homo sapiens.',
                'cover_image_url' => 'https://images-na.ssl-images-amazon.com/images/I/41y-y139dXL._SX331_BO1,204,203,200_.jpg',
                'total_copies' => 5,
                'available_copies' => 5,
            ],
        ];

        foreach ($books as $bookData) {
            Book::create($bookData);
        }
    }
}