<?php
namespace App\Model;

use App\Service\Config;

class Game
{
    private ?int $id = null;
    private ?string $title = null;
    private ?string $genre = null;
    private ?string $platform = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Game
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): Game
    {
        $this->title = $title;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): Game
    {
        $this->genre = $genre;

        return $this;
    }

    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    public function setPlatform(?string $platform): Game
    {
        $this->platform = $platform;

        return $this;
    }

    public static function fromArray($array): Game
    {
        $game = new self();
        $game->fill($array);

        return $game;
    }

    public function fill($array): Game
    {
        if (isset($array['id']) && ! $this->getId()) {
            $this->setId($array['id']);
        }
        if (isset($array['title'])) {
            $this->setTitle($array['title']);
        }
        if (isset($array['genre'])) {
            $this->setGenre($array['genre']);
        }
        if (isset($array['platform'])) {
            $this->setPlatform($array['platform']);
        }

        return $this;
    }

    public static function findAll(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM game';
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $games = [];
        $gamesArray = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($gamesArray as $gameArray) {
            $games[] = self::fromArray($gameArray);
        }

        return $games;
    }

    public static function find($id): ?Game
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM game WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $id]);

        $gameArray = $statement->fetch(\PDO::FETCH_ASSOC);
        if (! $gameArray) {
            return null;
        }

        return Game::fromArray($gameArray);
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if (! $this->getId()) {
            $sql = "INSERT INTO game (title, genre, platform) VALUES (:title, :genre, :platform)";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'title' => $this->getTitle(),
                'genre' => $this->getGenre(),
                'platform' => $this->getPlatform(),
            ]);

            $this->setId((int) $pdo->lastInsertId());
        } else {
            $sql = "UPDATE game SET title = :title, genre = :genre, platform = :platform WHERE id = :id";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'title' => $this->getTitle(),
                'genre' => $this->getGenre(),
                'platform' => $this->getPlatform(),
                'id' => $this->getId(),
            ]);
        }
    }

    public function delete(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = "DELETE FROM game WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $statement->execute([
            'id' => $this->getId(),
        ]);

        $this->setId(null);
        $this->setTitle(null);
        $this->setGenre(null);
        $this->setPlatform(null);
    }
}
