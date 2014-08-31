<?php

namespace Wzc\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Operator
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class User extends BaseEntity implements UserInterface
{

    /**
     * @ORM\OneToMany(targetEntity="ForumQuestion", mappedBy="author")
     */
    protected $forumQuestions;

    /**
     * @ORM\OneToMany(targetEntity="ForumAnswer", mappedBy="author")
     */
    protected $forumAnswers;

    /**
     * @ORM\OneToMany(targetEntity="File", mappedBy="user")
     */
    protected $files;

    /**
     * @ORM\OneToMany(targetEntity="Faq", mappedBy="user", orphanRemoval=false)
     */
    protected $questions;

    /**
     * @ORM\OneToMany(targetEntity="Consultation", mappedBy="user", orphanRemoval=false)
     */
    protected $consultations;



    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank( message = "поле E-mail обязательно для заполнения" )
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank( message = "поле пароль" )
     */
    protected $password;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $birthdate;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $city;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $surName;

    /**
     * @ORM\Column(type="string", length=150)
     */
    protected $salt;

    /**
     * @ORM\Column(type="string", length=150)
     */
    protected $roles;


    public function __construct(){
        $this->roles    = 'ROLE_USER';
        $this->questions = new ArrayCollection();
        $this->forumQuestions = new ArrayCollection();
        $this->forumAnswers = new ArrayCollection();
        $this->consultations = new ArrayCollection();
        $this->files = new ArrayCollection();
    }

    static public function getRolesNames(){
        return array(
            "ADMIN" => "Administrateur",
            "ANIMATOR" => "Animateur",
            "USER" => "Utilisateur",
        );
    }

    public function equals(UserInterface $user)
    {
        return md5($this->getUsername()) == md5($user->getUsername());
    }

    public function __toString()
    {
//        return $this->username;
        if ($this->lastName || $this->firstName || $this->surName){
            return $this->lastName.'. '.$this->firstName();
        }else{
            return 'Анонимный № '.$this->id;
        }
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return explode(';', $this->roles);
    }



    /**
     * @param mixed $roles
     */
    public function setRoles($roles)
    {
        if (is_array($roles)) {
            $roles = implode($roles, ';');
        }

        $this->roles = $roles;
    }

    public function removeRole($role)
    {
        $roles = explode(';', $this->roles);
        $key   = array_search($role, $roles);

        if ($key !== false) {
            unset($roles[$key]);
            $this->roles = implode($roles, ';');
        }
    }

    public function checkRole($role)
    {
        $roles = explode(';', $this->roles);

        return in_array($role, $roles);
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Сброс прав пользователя.
     */
    public function eraseCredentials()
    {
        return true;
    }

    public function isEqualTo(UserInterface $user)
    {
        return md5($this->getUsername()) == md5($user->getUsername());
    }

    /**
     * Сериализуем только id, потому что UserProvider сам перезагружает остальные свойства пользователя по его id
     *
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id
            ) = unserialize($serialized);
    }
    /**
     * @return mixed
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param mixed $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @param mixed $birthdate
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
    }

    /**
     * @return mixed
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $questions
     */
    public function setQuestions($questions)
    {
        $this->questions = $questions;
    }

    /**
     * @return mixed
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    public function addQuestion($question){
        $this->questions[] = $question;
    }

    public function removeQuestion($question){
        $this->questions->removeElement($question);
    }

    /**
     * @param mixed $surName
     */
    public function setSurName($surName)
    {
        $this->surName = $surName;
    }

    /**
     * @return mixed
     */
    public function getSurName()
    {
        return $this->surName;
    }

    /**
     * @param mixed $consultations
     */
    public function setConsultations($consultations)
    {
        $this->consultations = $consultations;
    }

    /**
     * @return mixed
     */
    public function getConsultations()
    {
        return $this->consultations;
    }

    public function addConsultation($consultation){
        $this->consultations[] = $consultation;
    }

    public function removeConsultation($consultation){
        $this->consultations->removeElement($consultation);
    }

    /**
     * @param mixed $forumQuestions
     */
    public function setForumQuestions($forumQuestions)
    {
        $this->forumQuestions = $forumQuestions;
    }

    /**
     * @return mixed
     */
    public function getForumQuestions()
    {
        return $this->forumQuestions;
    }

    public function addForumQuestion($question){
        $this->forumQuestions[] = $question;
    }

    public function removeForumQuestion($question){
        $this->forumQuestions->removeElement($question);
    }

    /**
     * @param mixed $forumAnswers
     */
    public function setForumAnswers($forumAnswers)
    {
        $this->forumAnswers = $forumAnswers;
    }

    /**
     * @return mixed
     */
    public function getForumAnswers()
    {
        return $this->forumAnswers;
    }

    public function addForumAnswer($answer){
        $this->forumAnswers[] = $answer;
    }

    public function removeForumAnswer($answer){
        $this->forumAnswers->removeElement($answer);
    }

    /**
     * @param mixed $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

    public function addFile($file){
        $this->files[] = $file;
    }

    public function removeFile($file){
        $this->files->removeElement($file);
    }


}