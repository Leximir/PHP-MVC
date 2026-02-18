<?php

namespace app\core;

abstract class Model
{
    public const RULE_REQUIRED = "required"; // Pravilo validacije: polje ne smije biti prazno.
    public const RULE_EMAIL = "email";       // Pravilo validacije: vrijednost mora biti validna email adresa.
    public const RULE_MIN = "min";           // Pravilo validacije: minimalna dužina stringa (npr. ['min' => 8]).
    public const RULE_MAX = "max";           // Pravilo validacije: maksimalna dužina stringa (npr. ['max' => 20]).
    public const RULE_MATCH = "match";       // Pravilo validacije: vrijednost mora odgovarati drugom polju (npr. passwordConfirm == password).

    public array $errors = [];               // Lista grešaka validacije po poljima (format: ['field' => ['poruka1', 'poruka2']]).
    public function loadData($data)
    {
        foreach ($data as $key => $value) {              // Prolazi kroz sve parove (ključ => vrijednost) iz poslanih podataka.
            if (property_exists($this, $key)) {          // Provjerava da li model ima property sa tim imenom (npr. "email", "password").
                $this->{$key} = $value;                  // Ako postoji, upisuje vrijednost u taj property modela.
            }
        }
    }

    public function addError(string $attribute, string $rule, $params = [])
    {
        $message = $this->errorMessages()[$rule] ?? '';                 // Uzima šablon poruke za dato pravilo (npr. "Min length... {min}").

        foreach ($params as $key => $value) {                           // Prolazi kroz parametre pravila (npr. ['min' => 8])...
            $message = str_replace("{$key}", $value, $message);  // ...i ubacuje vrijednosti u poruku zamjenom placeholder-a (npr. {min} -> 8).
        }

        $this->errors[$attribute][] = $message;                         // Dodaje finalnu poruku greške u listu grešaka za dato polje (attribute).
    }

    public function errorMessages()
    {
        return [
            self::RULE_REQUIRED => 'This field is required',                 // Poruka kada je polje obavezno, a nije popunjeno.
            self::RULE_EMAIL => 'This field must be valid email address',    // Poruka kada email nije validnog formata.
            self::RULE_MIN => "Min length of this field must be {min}",      // Poruka za minimalnu dužinu (placeholder {min} se zamjenjuje vrijednošću).
            self::RULE_MAX => 'Max length of this field must be {max}',      // Poruka za maksimalnu dužinu (placeholder {max} se zamjenjuje vrijednošću).
            self::RULE_MATCH => 'This field must be the same as {match}',    // Poruka kada polje mora odgovarati drugom (npr. passwordConfirm).
        ];
    }
    abstract public function rules(): array; // Apstraktna metoda: svaka child Model klasa mora definisati svoja pravila validacije i vratiti ih kao niz.

    public function validate()
    {

        foreach ($this->rules() as $attribute => $rules) {  // Prolazi kroz sva pravila validacije koja definiše konkretan model (preko rules()).

            $value = $this->{$attribute};                   // Uzima trenutnu vrijednost atributa (property) iz modela (npr. $this->email, $this->password).


            foreach ($rules as $rule) {     // Svaki atribut može imati više pravila (required, email, min, max, match...).

                $ruleName = $rule;          // Podrazumijevano pretpostavlja da je rule string (npr. "required").



                if (!is_string($ruleName)) {       // Ako rule nije string, onda je u formatu niza (npr. ['min' => 8] ili [self::RULE_MIN, 'min' => 8]),
                    $ruleName = $rule[0];          // pa uzimamo ime pravila iz prvog elementa.
                }


                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addError($attribute, self::RULE_REQUIRED); // REQUIRED: ako je polje obavezno, a vrijednost je prazna (false/''/null), dodaj grešku.
                }


                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($attribute, self::RULE_EMAIL);    // EMAIL: provjera da li je vrijednost validan email format.
                }


                if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {  // MIN: provjera minimalne dužine stringa (npr. password >= 8).
                    $this->addError($attribute, self::RULE_MIN, $rule);          // Prosleđujemo $rule da se {min} može zamijeniti u poruci.
                }


                if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {  // MAX: provjera maksimalne dužine stringa.
                    $this->addError($attribute, self::RULE_MAX, $rule);          // Prosleđujemo $rule da se {max} može zamijeniti u poruci.
                }


                if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {  // MATCH: provjera da li se vrijednost poklapa sa vrijednošću drugog polja (npr. passwordConfirm == password).
                    $this->addError($attribute, self::RULE_MATCH, $rule);               // Prosleđuje parametre (npr. ['match' => 'password']) radi poruke.
                }
            }
        }

        return empty($this->errors); // Vraća true ako nema grešaka, false ako postoji bar jedna validaciona greška.
    }

    public function hasError($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError($attribute)
    {
        return $this->errors[$attribute][0] ?? false;
    }
}