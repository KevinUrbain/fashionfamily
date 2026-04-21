<?php
class Cart
{
    private static string $sessionKey = 'cart';

    /**
     * Retourne tous les articles présents dans le panier.
     *
     * @return array<int, array<string, mixed>> Tableau indexé par l'identifiant de l'article.
     */
    public static function getItems(): array
    {
        return $_SESSION[self::$sessionKey] ?? [];
    }

    /**
     * Ajoute un article au panier ou incrémente sa quantité si déjà présent.
     * La quantité est plafonnée au stock disponible de l'article.
     *
     * @param array<string, mixed> $article  Données de l'article (doit contenir id, title, price, image_path, quantity).
     * @param int                  $quantity Quantité à ajouter (minimum 1).
     */
    public static function add(array $article, int $quantity = 1): void
    {
        $id = (int) $article['id'];
        $maxQty = (int) $article['quantity'];
        $cart = self::getItems();

        if (isset($cart[$id])) {
            $newQty = $cart[$id]['quantity'] + $quantity;
            $cart[$id]['quantity'] = min($newQty, $maxQty);
        } else {
            $cart[$id] = [
                'id'           => $id,
                'title'        => $article['title'],
                'price'        => (float) $article['price'],
                'image_path'   => $article['image_path'],
                'quantity'     => min($quantity, $maxQty),
                'max_quantity' => $maxQty,
            ];
        }

        $_SESSION[self::$sessionKey] = $cart;
    }

    /**
     * Modifie la quantité d'un article dans le panier.
     * Supprime l'article si la quantité est inférieure ou égale à zéro.
     * La quantité est plafonnée au stock maximum enregistré lors de l'ajout.
     *
     * @param int $articleId Identifiant de l'article.
     * @param int $quantity  Nouvelle quantité souhaitée.
     */
    public static function update(int $articleId, int $quantity): void
    {
        $cart = self::getItems();

        if (!isset($cart[$articleId])) {
            return;
        }

        if ($quantity <= 0) {
            self::remove($articleId);
            return;
        }

        $cart[$articleId]['quantity'] = min($quantity, $cart[$articleId]['max_quantity']);
        $_SESSION[self::$sessionKey] = $cart;
    }

    /**
     * Retire un article du panier.
     *
     * @param int $articleId Identifiant de l'article à retirer.
     */
    public static function remove(int $articleId): void
    {
        $cart = self::getItems();
        unset($cart[$articleId]);
        $_SESSION[self::$sessionKey] = $cart;
    }

    /**
     * Vide entièrement le panier.
     */
    public static function clear(): void
    {
        $_SESSION[self::$sessionKey] = [];
    }

    /**
     * Calcule le montant total du panier (prix × quantité pour chaque article).
     *
     * @return float Montant total en euros.
     */
    public static function getTotal(): float
    {
        $total = 0.0;
        foreach (self::getItems() as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    /**
     * Retourne le nombre total d'articles dans le panier (somme des quantités).
     *
     * @return int
     */
    public static function getCount(): int
    {
        $count = 0;
        foreach (self::getItems() as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }

    /**
     * Indique si le panier est vide.
     *
     * @return bool true si le panier ne contient aucun article.
     */
    public static function isEmpty(): bool
    {
        return empty(self::getItems());
    }

    /**
     * Vérifie si un article spécifique est déjà dans le panier.
     *
     * @param int $articleId Identifiant de l'article.
     * @return bool true si l'article est présent.
     */
    public static function has(int $articleId): bool
    {
        return isset(self::getItems()[$articleId]);
    }
}
