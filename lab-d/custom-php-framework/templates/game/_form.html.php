<?php
    /** @var $game ?\App\Model\Game */
?>

<div class="form-group">
    <label for="title">Title</label>
    <input type="text" id="title" name="game[title]" value="<?= $game ? $game->getTitle() : '' ?>">
</div>

<div class="form-group">
    <label for="genre">Genre</label>
    <input type="text" id="genre" name="game[genre]" value="<?= $game ? $game->getGenre() : '' ?>">
</div>

<div class="form-group">
    <label for="platform">Platform</label>
    <input type="text" id="platform" name="game[platform]" value="<?= $game ? $game->getPlatform() : '' ?>">
</div>

<div class="form-group">
    <label></label>
    <input type="submit" value="Submit">
</div>
