<?php
class Validator
{
    private array $errors = [];

    /**
     * Valide un tableau de données selon un ensemble de règles.
     * Règles supportées : required, email, min:{n}, max:{n}.
     *
     * @param array<string, mixed>             $data  Données à valider (ex. $_POST filtré).
     * @param array<string, array<int, string>> $rules Règles par champ (ex. ['email' => ['required', 'email']]).
     * @return array<string, array<int, string>> Tableau d'erreurs indexé par nom de champ.
     */
    public function validate(array $data, array $rules): array
    {
        $this->errors = [];

        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? '';

            foreach ($fieldRules as $rule) {
                if ($rule === 'required' && empty($value)) {
                    $this->errors[$field][] = "Le champ $field est obligatoire.";
                }

                if ($rule === 'email' && !empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = "Le champ $field doit être un email valide.";
                }

                if (str_starts_with($rule, 'min:')) {
                    $min = (int) substr($rule, 4);
                    if (strlen((string) $value) < $min) {
                        $this->errors[$field][] = "Le champ $field doit contenir au moins $min caractères.";
                    }
                }

                if (str_starts_with($rule, 'max:')) {
                    $max = (int) substr($rule, 4);
                    if (strlen((string) $value) > $max) {
                        $this->errors[$field][] = "Le champ $field ne peut pas dépasser $max caractères.";
                    }
                }
            }
        }

        return $this->errors;
    }

    /**
     * Indique si la dernière validation a produit des erreurs.
     *
     * @return bool true si au moins une erreur a été détectée.
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Retourne le tableau complet des erreurs de validation indexé par champ.
     *
     * @return array<string, array<int, string>>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Aplatit le tableau d'erreurs (tableau de tableaux → tableau simple)
     *
     * @param array<string, array<int, string>> $errors Tableau d'erreurs indexé par champ.
     * @return array<int, string> Liste plate des messages d'erreur.
     */
    public static function flattenErrors(array $errors): array
    {
        if (empty($errors)) {
            return [];
        }
        return array_merge(...array_values($errors));
    }
}
