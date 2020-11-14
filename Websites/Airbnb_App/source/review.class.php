<?php

class Review {

  protected $guest_name;
  protected $review;
  protected $stars;

  public function __construct($name, $review, $stars) {
      $this->guest_name = $name;
      $this->review = $review;
      $this->stars = $stars;
  }

  public function getName() {
    return $this->guest_name;
  }

  public function getReview() {
    return $this->review;
  }

  public function getStars() {
    return $this->stars;
  }
}

 ?>
