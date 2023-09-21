<?php
     function createSlug($title) {
        $slug = preg_replace('/-+/', '-', strtr($title, ['é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e', 'à' => 'a', 'â' => 'a', 'ä' => 'a', 'ô' => 'o', 'ö' => 'o', 'û' => 'u', 'ü' => 'u', 'ç' => 'c']));
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $slug));
        $slug = preg_replace('/-+/', '-', $slug);
        return $slug;
    }
?>