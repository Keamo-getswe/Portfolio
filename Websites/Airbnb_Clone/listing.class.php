<?php
class Listing {

	private $identifier;
	private $accommodationType;
	private $listingName;
	private $hostId;
	private $strAddress;
	private $suburb;
	private $town;
	private $addressCoordinates;
	private $availableRooms;
	private $accomodationCapacity; //The maximum number of people allowed
	private $tarrif;
    private $minBookingDays;
    private $tags;
	private $description;
	private $image1;
	private $image2;
	private $image3;
    private $image4;
    private $image5;

    public function __construct($accommodationType, $listingName, $hostId, $strAddress, $suburb, $town, $addressCoordinates, $availableRooms,
        $accommodationCapacity, $tarrif, $minBookingDays, $tags, $description, $image1, $image2, $image3, $image4, $image5) {

		$this->identifier = -1;
		$this->accommodationType = $accommodationType;
		$this->listingName =  $listingName;
		$this->hostId = $hostId;
		$this->strAddress = $strAddress;
		$this->suburb = $suburb;
        $this->town = $town;
		$this->addressCoordinates = $addressCoordinates;
		$this->availableRooms = $availableRooms;
		$this->accomodationCapacity = $accomodationCapacity;
		$this->tarrif = $tarrif;
		$this->minBookingDays = $minBookingDays;
		$this->tags = $tags;
		$this->description = $description;
		$this->setImage1($image1);
		$this->setImage2($image2);
		$this->setImage3($image3);
		$this->setImage4($image4);
		$this->setImage5($image5);
	}

	/* Getter Functions */

	public function getIdentifier() {
		return $this->identifier;
	}

	public function getAccommodationType() {
		return $this->accommodationType;
	}

	public function getListingName() {
		return $this->listingName;
	}

	public function getHostId() {
		return $this->hostId;
	}

	public function getAddress() {
		return $this->strAddress;
	}

	public function getSuburb() {
		return $this->suburb;
	}

	public function getTown() {
		return $this->town;
	}

	public function getAddressCoordinates() {
		return $this->addressCoordinates;
	}

	public function getAvailableRooms() {
		return $this->availableRooms;
	}

	public function getAccommodationCapacity() {
		return $this->accommodationCapacity;
	}

	public function getTarrif(){
		return $this->tarrif;
	}

	public function getMinBookingDays() {
		return $this->minBookingDays;
	}

	public function getTags() {
		return $this->tags;
	}

	public function getDescription() {
		return $this->description;
	}

	public function getImage1($rawForm=FALSE) {
		if ($rawForm == TRUE) {
			return $this->image1;
		} else {
			return 'data:image/jpeg;base64,'. base64_encode($this->image1);
		}
	}

	public function getImage2($rawForm=FALSE) {
		if ($rawForm == TRUE) {
			return $this->image2;
		} else {
			return 'data:image/jpeg;base64,'. base64_encode($this->image2);
		}
	}

	public function getImage3($rawForm=FALSE) {
		if ($rawForm == TRUE) {
			return $this->image3;
		} else {
			return 'data:image/jpeg;base64,'. base64_encode($this->image3);
		}
	}

	public function getImage4($rawForm=FALSE) {
		if ($rawForm == TRUE) {
			return $this->image4;
		} else {
			return 'data:image/jpeg;base64,'. base64_encode($this->image4);
		}
	}

	public function getImage5($rawForm=FALSE) {
		if ($rawForm == TRUE) {
			return $this->image5;
		} else {
			return 'data:image/jpeg;base64,'. base64_encode($this->image5);
		}
	}

	/* Setter Functions */

	public function setIdentifier($id) {
		$this->identifier = $id;
	}

	public function setAccommodationType($accommodationType) {
		$this->accommodationType = $accommodationType;
	}

	public function setListingName($listingName) {
		$this->listingName = $listingName;
	}

	public function setHostId($hostId) {
		$this->hostId = $hostId;
	}

	public function setAddress($strAddress) {
		$this->strAddress = $strAddress;
	}

	public function setSuburb($suburb) {
		$this->suburb = $suburb;
	}

	public function setTown($town) {
		$this->town = $town;
	}

	public function setAddressCoordinates($addressCoordinates) {
		$this->addressCoordinates = $addressCoordinates;
	}

	public function setAvailableRooms($availableRooms) {
		$this->availableRooms = $availableRooms;
	}

	public function setAccommodationCapacity($accommodationCapacity) {
		$this->accommodationCapacity = $accommodationCapacity;
	}

	public function setTarrif($tarrif){
		$this->tarrif = $tarrif;
	}

	public function setMinBookingDays($minBookingDays) {
		$this->minBookingDays = $minBookingDays;
	}

	public function setTags($tags) {
		$this->tags = $tags;
	}

	public function setDescription($description) {
		$this->description = $description;
	}

	public function setImage1($image) {
		if ($image == NULL) {
			$this->image1 = addslashes(file_get_contents("./assets/images/standardListEmpty.jpg"));
		} else {
			$this->image1 = $image;
		}
	}

	public function setImage2($image) {
		if ($image == NULL) {
			$this->image2 = addslashes(file_get_contents("./assets/images/standardListEmpty.jpg"));
		} else {
			$this->image2 = $image;
		}
	}

	public function setImage3($image) {
		if ($image == NULL) {
			$this->image3 = addslashes(file_get_contents("./assets/images/standardListEmpty.jpg"));
		} else {
			$this->image3 = $image;
		}
	}

	public function setImage4($image) {
		if ($image == NULL) {
			$this->image4 = addslashes(file_get_contents("./assets/images/standardListEmpty.jpg"));
		} else {
			$this->image4 = $image;
		}
	}

	public function setImage5($image) {
		if ($image == NULL) {
			$this->image5 = addslashes(file_get_contents("./assets/images/standardListEmpty.jpg"));
		} else {
			$this->image5 = $image;
		}
	}

	public function __toString() {
		return "Host ID: " . $this->hostId .
		"\nAccommodation Name: " . $this->listingName .
		"\nTown/City: " . $this->town .
		"\nAddress: " . $this->strAddress .
		"\nDescription: ". $this->description .
		"\nTarriff: " . $this->tarrif;
	}
}
?>
