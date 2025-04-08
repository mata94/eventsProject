<?php

namespace App\Model;

use Symfony\Component\HttpFoundation\RequestStack;

class FilterRequest
{
    const PAGE = 'page';
    const OFFSET = 'offset';
    const LIMIT = 'limit';
    const DIRECTION = 'direction';
    const START_DATE = 'startDate';
    const END_DATE = 'endDate';
    const START_DATE_TIME = 'startDateTime';
    const END_DATE_TIME = 'endDateTime';
    const STUDENTSTATUS = 'studentStatus';
    const STATUS = 'status';
    const RATING = 'rating';
    const COMPANY = 'company';
    const SEARCH = 'search';
    const TYPE = 'type';
    const SIBLING = 'sibling';
    const USER_TYPE = 'userType';
    const LESSON_TYPE = 'lessonType';
    const GENRE = 'genre';
    const SEASON = 'season';
    const CATEGORY = 'category';
    const COMMAND = 'command';
    const CITY = 'city';
    const COUNTRY = 'country';
    const CLUB = 'club';
    const RACE = 'race';
    const PACKAGE = 'package';
    const WEIGHT = 'weight';
    const SEX = 'sex';
    const HAND = 'hand';
    const DAY = 'day';
    const USER = 'user';
    const MONTH = 'month';
    const YEAR = 'year';
    const TRANSPORT = 'transport';
    const PRODUCT = 'product';
    const SPECIAL_NEEDS = 'specialNeeds';
    const START_ZONE = 'startZone';
    const PERSONALITY = 'personality';
    const FREQUENCY = 'frequency';
    const ACTIVE = 'active';
    const REGISTERED = 'registered';
    const ORGANIZATION = 'organization';
    const PROVINCE = 'province';
    const USERUUID = 'userUuid';
    const TEACHERID = 'teacherId';
    const STUDENTID = 'studentId';
    const INSTRUMENT = 'instrument';
    const LOCATION = 'location';
    const TMP_PASS = 'tmpPass';
    const TMP_PAR1_PASS = 'tmpPa1Pass';
    const TMP_PAR2_PASS = 'tmpPa2Pass';
    const PLAYING_LEVEL = 'playingLevel';
    const HOMEWORK_STATUS = 'homeworkStatus';
    const PAYMENT_METHOD = 'paymentMethod';
    const START_FORMAT = 'Y-m-d 00:00:00';
    const END_FORMAT = 'Y-m-d 23:59:59';
    const DATETIME_FORMAT = 'Y-m-d H:i:s';
    const STUDENT_CHECK = 'studentCheck';
    const TEACHER_CHECK = 'teacherCheck';
    const RACETYPE = 'raceType';
    const TOTAL_MARATHONS = 'totalMarathons';
    const ZONE = 'zona';
    const TICKET_TYPE = 'ticketType';
    const PURCHASED_TYPE = 'purchasedType';
    const PAYMENT_STATUS = 'paymentStatus';
    const VALUE_CHARACTER = '--';

    private $tmpPass;
    private $tmpPa1Pass;
    private $tmpPa2Pass;
    private $page;
    private $offset;
    private $limit;
    private $orderBy = 'id';
    private $direction;
    private $startDate;
    private $endDate;
    private $startDateTime;
    private $endDateTime;
    private $startZone;
    private $status;
    private $rating;
    private $company;
    private $studentStatus;
    private $search;
    private $type;
    private $userType;
    private $lessonType;
    private $genre;
    private $season;
    private $province;
    private $location;
    private $transport;
    private $product;
    private $category;
    private $city;
    private $specialNeeds;
    private $personality;
    private $country;
    private $club;
    private $race;
    private $package;
    private $sex;
    private $day;
    private $user;
    private $month;
    private $year;
    private $sibling;
    private $teacherId;
    private $studentId;
    private $frequency;
    private $active;
    private $registered;
    private $organization;
    private $userUuid;
    private $teacher;
    private $student;
    private $playingLevel;
    private $homeworkStatus;
    private $instrument;
    private $paymentMethod;
    private $studentCheck;
    private $teacherCheck;
    private $raceType;
    private $totalMarathons;
    private $zone;
    private $onlyRacer = true;
    private $ticketType;
    private $purchasedType;
    private $paymentStatus;

    /** @var \Symfony\Component\HttpFoundation\Request */
    private $request;

    public function __construct(
        RequestStack $requestStack
    )
    {
        $this->request = $requestStack->getCurrentRequest();

        if (!$this->request) {
            return;
        }

        $this->page = $this->request->query->get(self::PAGE, 1);
        $this->limit = $this->request->query->get(self::LIMIT, 10);
        $this->direction = $this->request->query->get(self::DIRECTION);
        $this->startDate = $this->request->query->get(self::START_DATE);
        $this->endDate = $this->request->query->get(self::END_DATE);
        $this->startDateTime = $this->request->query->get(self::START_DATE_TIME);
        $this->endDateTime = $this->request->query->get(self::END_DATE_TIME);
        $this->startZone = $this->request->query->get(self::START_ZONE);
        $this->status = $this->request->query->get(self::STATUS);
        $this->rating = $this->request->query->get(self::RATING);
        $this->company = $this->request->query->get(self::COMPANY);
        $this->club = $this->request->query->get(self::CLUB);
        $this->race = $this->request->query->get(self::RACE);
        $this->package = $this->request->query->get(self::PACKAGE);
        $this->search = $this->request->query->get(self::SEARCH);
        $this->type = $this->request->query->get(self::TYPE);
        $this->userType = $this->request->query->get(self::USER_TYPE);
        $this->lessonType = $this->request->query->get(self::LESSON_TYPE);
        $this->genre = $this->request->query->get(self::GENRE);
        $this->location = $this->request->query->get(self::LOCATION);
        $this->season = $this->request->query->get(self::SEASON);
        $this->category = $this->request->query->get(self::CATEGORY);
        $this->command = $this->request->query->get(self::COMMAND);
        $this->city = $this->request->query->get(self::CITY);
        $this->country = $this->request->query->get(self::COUNTRY);
        $this->weight = $this->request->query->get(self::WEIGHT);
        $this->sex = $this->request->query->get(self::SEX);
        $this->hand = $this->request->query->get(self::HAND);
        $this->province = $this->request->query->get(self::PROVINCE);
        $this->day = $this->request->query->get(self::DAY);
        $this->user = $this->request->query->get(self::USER);
        $this->transport = $this->request->query->get(self::TRANSPORT);
        $this->product = $this->request->query->get(self::PRODUCT);
        $this->teacherId = $this->request->query->get(self::TEACHERID);
        $this->studentId = $this->request->query->get(self::STUDENTID);
        $this->frequency = $this->request->query->get(self::FREQUENCY);
        $this->active = $this->request->query->get(self::ACTIVE);
        $this->registered = $this->request->query->get(self::REGISTERED);
        $this->organization = $this->request->query->get(self::ORGANIZATION);
        $this->userUuid = $this->request->query->get(self::USERUUID);
        $this->sibling = $this->request->query->get(self::SIBLING);
        $this->year = $this->request->query->get(self::YEAR);
        $this->month = $this->request->query->get(self::MONTH);
        $this->specialNeeds = $this->request->query->get(self::SPECIAL_NEEDS);
        $this->personality = $this->request->query->get(self::PERSONALITY);
        $this->tmpPass = $this->request->query->get(self::TMP_PASS);
        $this->tmpPa1Pass = $this->request->query->get(self::TMP_PAR1_PASS);
        $this->tmpPa2Pass = $this->request->query->get(self::TMP_PAR2_PASS);
        $this->instrument = $this->request->query->get(self::INSTRUMENT);
        $this->playingLevel = $this->request->query->get(self::PLAYING_LEVEL);
        $this->studentStatus = $this->request->query->get(self::STUDENTSTATUS);
        $this->studentCheck = $this->request->query->get(self::STUDENT_CHECK);
        $this->teacherCheck = $this->request->query->get(self::TEACHER_CHECK);
        $this->homeworkStatus = $this->request->query->get(self::HOMEWORK_STATUS);
        $this->paymentMethod = $this->request->query->get(self::PAYMENT_METHOD);
        $this->raceType = $this->request->query->get(self::RACETYPE);
        $this->totalMarathons = $this->request->query->get(self::TOTAL_MARATHONS);
        $this->zone = $this->request->query->get(self::ZONE);
        $this->ticketType = $this->request->query->get(self::TICKET_TYPE);
        $this->purchasedType = $this->request->query->get(self::PURCHASED_TYPE);
        $this->paymentStatus = $this->request->query->get(self::PAYMENT_STATUS);
    }

    public function setOnlyRacer(bool $value): void
    {
        $this->onlyRacer = $value;
    }

    public function getOnlyRacer(): bool
    {
        return $this->onlyRacer;
    }

    public function getCommand()
    {
        if($this->command && $this->command == 'true'){
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        if($this->startDate instanceof \DateTime){
            return $this->startDate->format(self::START_FORMAT);
        }

        if($this->startDate && $this->isGoodValue($this->startDate)){
            $dt =  new \DateTime($this->startDate);
            return $dt->format(self::START_FORMAT);
        }
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        if($this->endDate instanceof \DateTime){
            return $this->endDate->format(self::END_FORMAT);
        }

        $this->isGoodValue($this->endDate);

        if($this->endDate && $this->isGoodValue($this->endDate)){
            $dt =  new \DateTime($this->endDate);
            return $dt->format(self::END_FORMAT);
        }
    }

    /**
     * @return mixed
     */
    public function getStartDateTime()
    {
        if($this->startDateTime && $this->isGoodValue($this->startDateTime)){
            $dt =  new \DateTime($this->startDateTime);
            return $dt->format(self::DATETIME_FORMAT);
        }
    }


    /**
     * @return mixed
     */
    public function getEndDateTime()
    {
        $this->isGoodValue($this->endDateTime);

        if($this->endDateTime && $this->isGoodValue($this->endDateTime)){
            $dt =  new \DateTime($this->endDateTime);
            return $dt->format(self::DATETIME_FORMAT);
        }
    }

    private function isGoodValue($value)
    {
        if(strpos($value, self::VALUE_CHARACTER) === false){
            return true;
        }
        return false;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * @return mixed
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return mixed
     */
    public function getOffset()
    {
        return (int) $this->offset;
    }

    public function incrementPage()
    {
        $this->page++;
    }

    /**
     * @param mixed $page
     */
    public function setPage($page): void
    {
        $this->page = $page;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param mixed $limit
     */
    public function setLimit($limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @return string
     */
    public function getOrderBy(): string
    {
        return $this->orderBy;
    }

    /**
     * @param string $orderBy
     */
    public function setOrderBy(string $orderBy): void
    {
        $this->orderBy = $orderBy;
    }

    /**
     * @return mixed
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param mixed $direction
     */
    public function setDirection($direction): void
    {
        $this->direction = $direction;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        if(!$this->type){
            $this->type = $type;
        }
    }

    /**
     * @return mixed
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * @param mixed $season
     */
    public function setSeason($season): void
    {
        $this->season = $season;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest(): \Symfony\Component\HttpFoundation\Request
    {
        return $this->request;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function setRequest(\Symfony\Component\HttpFoundation\Request $request): void
    {
        $this->request = $request;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country): void
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     */
    public function setWeight($weight): void
    {
        $this->weight = $weight;
    }

    /**
     * @return mixed
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @param mixed $sex
     */
    public function setSex($sex): void
    {
        $this->sex = $sex;
    }

    /**
     * @return mixed
     */
    public function getHand()
    {
        return $this->hand;
    }

    /**
     * @param mixed $hand
     */
    public function setHand($hand): void
    {
        $this->hand = $hand;
    }

    /**
     * @return mixed
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * @param mixed $teacher
     */
    public function setTeacher($teacher)
    {
        $this->teacher = $teacher;
    }

    /**
     * @return mixed
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * @param mixed $student
     */
    public function setStudent($student)
    {
        $this->student = $student;
    }

    /**
     * @return mixed
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * @param mixed $genre
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;
    }

    /**
     * @return mixed
     */
    public function getLessonType()
    {
        return $this->lessonType;
    }

    /**
     * @param mixed $lessonType
     */
    public function setLessonType($lessonType)
    {
        $this->lessonType = $lessonType;
    }

    /**
     * @return mixed
     */
    public function getUserUuid()
    {
        return $this->userUuid;
    }

    /**
     * @param mixed $userUuid
     */
    public function setUserUuid($userUuid)
    {
        $this->userUuid = $userUuid;
    }

    /**
     * @return mixed
     */
    public function getStudentCheck()
    {
        return $this->studentCheck;
    }

    /**
     * @param mixed $studentCheck
     */
    public function setStudentCheck($studentCheck)
    {
        $this->studentCheck = $studentCheck;
    }

    /**
     * @return mixed
     */
    public function getTeacherCheck()
    {
        return $this->teacherCheck;
    }

    /**
     * @param mixed $teacherCheck
     */
    public function setTeacherCheck($teacherCheck)
    {
        $this->teacherCheck = $teacherCheck;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getInstrument()
    {
        return $this->instrument;
    }

    /**
     * @param mixed $instrument
     */
    public function setInstrument($instrument)
    {
        $this->instrument = $instrument;
    }

    /**
     * @return mixed
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param mixed $day
     */
    public function setDay($day)
    {
        $this->day = $day;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getRegistered()
    {
        return $this->registered;
    }

    /**
     * @param mixed $registered;
     */
    public function setRegistered($registered)
    {
        $this->registered = $registered;
    }

    /**
     * @return mixed
     */
    public function getStudentStatus()
    {
        return $this->studentStatus;
    }

    /**
     * @param mixed $studentStatus
     */
    public function setStudentStatus($studentStatus)
    {
        $this->studentStatus = $studentStatus;
    }

    /**
     * @return mixed
     */
    public function getPlayingLevel()
    {
        return $this->playingLevel;
    }

    /**
     * @param mixed $playingLevel
     */
    public function setPlayingLevel($playingLevel)
    {
        $this->playingLevel = $playingLevel;
    }

    /**
     * @return mixed
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param mixed $province
     */
    public function setProvince($province)
    {
        $this->province = $province;
    }

    /**
     * @return mixed
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * @param mixed $userType
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;
    }

    /**
     * @return mixed
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * @param mixed $frequency
     */
    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return mixed
     */
    public function getHomeworkStatus()
    {
        return $this->homeworkStatus;
    }

    /**
     * @param mixed $homeworkStatus
     */
    public function setHomeworkStatus($homeworkStatus)
    {
        $this->homeworkStatus = $homeworkStatus;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getTeacherId()
    {
        return $this->teacherId;
    }

    /**
     * @param mixed $teacherId
     */
    public function setTeacherId($teacherId)
    {
        $this->teacherId = $teacherId;
    }

    /**
     * @return mixed
     */
    public function getStudentId()
    {
        return $this->studentId;
    }

    /**
     * @param mixed $studentId
     */
    public function setStudentId($studentId)
    {
        $this->studentId = $studentId;
    }

    /**
     * @return mixed
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @param mixed $paymentMethod
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @return mixed
     */
    public function getTmpPass()
    {
        return $this->tmpPass;
    }

    /**
     * @param mixed $tmpPass
     */
    public function setTmpPass($tmpPass)
    {
        $this->tmpPass = $tmpPass;
    }

    /**
     * @return mixed
     */
    public function getTmpPa1Pass()
    {
        return $this->tmpPa1Pass;
    }

    /**
     * @param mixed $tmpPa1Pass
     */
    public function setTmpPa1Pass($tmpPa1Pass)
    {
        $this->tmpPa1Pass = $tmpPa1Pass;
    }

    /**
     * @return mixed
     */
    public function getTmpPa2Pass()
    {
        return $this->tmpPa2Pass;
    }

    /**
     * @param mixed $tmpPa2Pass
     */
    public function setTmpPa2Pass($tmpPa2Pass)
    {
        $this->tmpPa2Pass = $tmpPa2Pass;
    }

    /**
     * @return mixed
     */
    public function getSibling()
    {
        return $this->sibling;
    }

    /**
     * @param mixed $sibling
     */
    public function setSibling($sibling)
    {
        $this->sibling = $sibling;
    }

    /**
     * @return mixed
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * @param mixed $transport
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;
    }

    /**
     * @return mixed
     */
    public function getSpecialNeeds()
    {
        return $this->specialNeeds;
    }

    /**
     * @param mixed $specialNeeds
     */
    public function setSpecialNeeds($specialNeeds)
    {
        $this->specialNeeds = $specialNeeds;
    }

    /**
     * @return mixed
     */
    public function getPersonality()
    {
        return $this->personality;
    }

    /**
     * @param mixed $personality
     */
    public function setPersonality($personality)
    {
        $this->personality = $personality;
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return bool|float|int|string|\Symfony\Component\HttpFoundation\InputBag|null
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param bool|float|int|string|\Symfony\Component\HttpFoundation\InputBag|null $organization
     */
    public function setOrganization($organization): void
    {
        $this->organization = $organization;
    }

    /**
     * @return bool|float|int|string|\Symfony\Component\HttpFoundation\InputBag|null
     */
    public function getRaceType()
    {
        return $this->raceType;
    }

    /**
     * @return bool|float|int|string|\Symfony\Component\HttpFoundation\InputBag|null
     */
    public function getClub()
    {
        return $this->club;
    }

    /**
     * @return bool|float|int|string|\Symfony\Component\HttpFoundation\InputBag|null
     */
    public function getRace()
    {
        return $this->race;
    }

    /**
     * @param bool|float|int|string|\Symfony\Component\HttpFoundation\InputBag|null $race
     */
    public function setRace($race): void
    {
        $this->race = $race;
    }

    /**
     * @return bool|float|int|string|\Symfony\Component\HttpFoundation\InputBag|null
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * @param bool|float|int|string|\Symfony\Component\HttpFoundation\InputBag|null $package
     */
    public function setPackage($package): void
    {
        $this->package = $package;
    }

    /**
     * @return bool|float|int|string|\Symfony\Component\HttpFoundation\InputBag|null
     */
    public function getStartZone()
    {
        return $this->startZone;
    }

    /**
     * @return bool|float|int|string|\Symfony\Component\HttpFoundation\InputBag|null
     */
    public function getTotalMarathons()
    {
        return $this->totalMarathons;
    }

    /**
     * @param bool|float|int|string|\Symfony\Component\HttpFoundation\InputBag|null $totalMarathons
     */
    public function setTotalMarathons($totalMarathons): void
    {
        $this->totalMarathons = $totalMarathons;
    }

    public function getZone(): float|bool|int|string|null
    {
        return $this->zone;
    }

    public function setZone(float|bool|int|string|null $zone): void
    {
        $this->zone = $zone;
    }

    public function getTicketType(): float|bool|int|string|null
    {
        return $this->ticketType;
    }

    public function setTicketType(float|bool|int|string|null $ticketType): void
    {
        $this->ticketType = $ticketType;
    }

    public function getPurchasedType(): float|bool|int|string|null
    {
        return $this->purchasedType;
    }

    public function setPurchasedType(float|bool|int|string|null $purchasedType): void
    {
        $this->purchasedType = $purchasedType;
    }

    public function getPaymentStatus(): float|bool|int|string|null
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(float|bool|int|string|null $paymentStatus): void
    {
        $this->paymentStatus = $paymentStatus;
    }
}