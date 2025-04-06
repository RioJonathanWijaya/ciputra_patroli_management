<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;

class FirebaseService
{
    protected Database $database;

    public function __construct()
    {
        $this->database = (new Factory)
            ->withServiceAccount(config('firebase.projects.app.credentials'))
            ->createDatabase();
    }

    /**
     * Get the Firebase database instance.
     */
    public function getDatabase(): Database
    {
        return $this->database;
    }

    /**
     * Get a specific reference from Firebase.
     */
    public function getReference(string $path)
    {
        return $this->database->getReference($path);
    }
}
