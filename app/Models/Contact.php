<?php

namespace App\Models;

class Contact
{
    public string $name;
    public string $telephone;
    public string $comment;

    public static function create(array $data)
    {
        $contact = new self;
        $contact->fill($data);

        return $contact;
    }
    public function fill(array $data) : Contact
    {
        $this->setName($data['name']);
        $this->setComment($data['comment']);
        $this->setTelephone($data['telephone']);
        return $this;
    }
    public function setName(string $name): string
    {
        return $this->name = $name;
    }

    public function setTelephone(string $telephone): string
    {
        return $this->telephone = $telephone;
    }

    public function setComment(string $comment): string
    {
        return $this->comment = $comment;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'telephone' => $this->telephone,
            'comment' => $this->comment
        ];
    }
}