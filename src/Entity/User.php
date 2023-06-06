<?php

namespace App\Entity;

use App\Entity\CRP\CoInvestigator;
use App\Entity\CRP\CollaborativeResearchProject;
use App\Entity\CRP\Deliverables;
use App\Repository\UserRepository;
use App\Entity\IrbCertificate as IrbCertificate;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;  
// use Symfony\Component\Security\Core\User\UserInterface;
/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=180, nullable=true, unique=true)
     */
    private $email;

 
    /**
     * @ORM\Column(type="string", length=180, nullable=true, unique=true)
     */
    private $username;

 /**
     * @ORM\Column(type="json", nullable=true)
     */   
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=Proposal::class, mappedBy="author")
     */
    private $proposals;

    /**
     * @ORM\OneToMany(targetEntity=App\Entity\Submission::class, mappedBy="author")
     */
    private $submissions; 
  
    /**
     * @ORM\ManyToOne(targetEntity=Review::class, inversedBy="reviewed_by")
     */
    private $reviews;
   
    /**
     * @ORM\ManyToOne(targetEntity=IrbCertificate::class, inversedBy="approvedBy")
     */
    private $certs; 
    /**
     * @ORM\OneToMany(targetEntity=ReviewAssignment::class, mappedBy="reviewer")
     */
    private $reviewAssignments; 
      /**
     * @ORM\OneToMany(targetEntity=App\Entity\IRB\IRBReviewAssignment::class, mappedBy="irbreviewer")
     */
    private $iRBReviewAssignments;
 
   /**
     * @ORM\ManyToMany(targetEntity=UserGroup::class, inversedBy="users")
     */
    private $userGroup; 
      /**
     * @ORM\ManyToMany(targetEntity=Permission::class, inversedBy="users_permissions")
     *
     * @var \Doctrine\Common\Collections\Collection
     */
    private $permissions;

  
    /**
     * @ORM\OneToMany(targetEntity=EditorialDecision::class, mappedBy="edited_by")
     */
    private $editorialDecisions;

    /**
     * @ORM\OneToMany(targetEntity=CallForProposal::class, mappedBy="approved_by")
     */
    private $callForProposals;

    /**
     * @ORM\OneToMany(targetEntity=Subscription::class, mappedBy="user")
     */
    private $subscriptions;

    
    /**
     * @ORM\OneToMany(targetEntity=Announcement::class, mappedBy="posted_by")
     */
    private $announcements;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSuperAdmin;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $registeredAt;

    /**
     * @ORM\OneToOne(targetEntity=UserInfo::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $userInfo;

    /**
     * @ORM\OneToMany(targetEntity=CoAuthor::class, mappedBy="researcher")
     */
    private $coAuthors;

    /**
     * @ORM\OneToMany(targetEntity=UserFeedback::class, mappedBy="user", orphanRemoval=true)
     */
    private $userFeedback;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_reviewer;

    /**
     * @ORM\OneToMany(targetEntity=TrainingParticipant::class, mappedBy="participant")
     */
    private $trainingParticipants;

 
      /**
     * @ORM\OneToMany(targetEntity=DirectorateOfficeUser::class, mappedBy="directorate" , orphanRemoval=true,cascade={"persist"})
     */
    private $directorateOfficeUsers;

    /**
     * @ORM\OneToMany(targetEntity=Publication::class, mappedBy="author", orphanRemoval=true)
     */
    private $publications;

    /**
     * @ORM\OneToMany(targetEntity=Chat::class, mappedBy="sentFrom", orphanRemoval=true)
     */
    private $chats;

    /**
     * @ORM\OneToMany(targetEntity=Chat::class, mappedBy="sentTo", orphanRemoval=true)
     */
    private $chatsTos;

    /**
     * @ORM\OneToMany(targetEntity=\App\Entity\IRB\ApplicationFeedback::class, mappedBy="feedbackFrom")
     */
    private $applicationFeedback;

    /**
     * @ORM\OneToMany(targetEntity=\App\Entity\CRP\CollaborativeResearchProject::class, mappedBy="PrincipalInvestigator", orphanRemoval=true)
     */
    private $collaborativeResearchProjects;

  

    /**
     * @ORM\ManyToMany(targetEntity=CollaborativeResearchProject::class, mappedBy="coInvestigators")
     */
    private $collaborativeResearchCoAuthorships;

    /**
     * @ORM\OneToMany(targetEntity=Deliverables::class, mappedBy="responsibleBody")
     */
    private $deliverables;

    

   

     
    public function __construct()
    {
        $this->isSuperAdmin =0;
        $this->isActive =1;
        $this->registeredAt=new \DateTime('now');
        $this->userGroup = new ArrayCollection();
        $this->proposals = new ArrayCollection(); 
        $this->institutionalReviewersBoards = new ArrayCollection();
        $this->reviewAssignments = new ArrayCollection();
        $this->IRBreviewAssignments = new ArrayCollection();
        $this->i_r_b_member = new ArrayCollection();
        $this->directorateOfficeUsers = new ArrayCollection();
        $this->permissions = new ArrayCollection();
        $this->editorialDecisions = new ArrayCollection();
        $this->submissions = new ArrayCollection();
        
        $this->callForProposals = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
        $this->announcements = new ArrayCollection();
        $this->coAuthors = new ArrayCollection();
        $this->userFeedback = new ArrayCollection();
        $this->trainingParticipants = new ArrayCollection();
        $this->publications = new ArrayCollection();
        $this->chats = new ArrayCollection();
        $this->chatsTos = new ArrayCollection();
        $this->applicationFeedback = new ArrayCollection();
        $this->collaborativeResearchProjects = new ArrayCollection();
        $this->collaborativeResearchCoAuthorships = new ArrayCollection();
        $this->deliverables = new ArrayCollection();
       }
  

      /**
     * @return Collection|Review[]
     */
    public function getDirectorateOfficeUsers(): Collection
    {
        return $this->directorateOfficeUsers;
    }

    public function addDirectorateOfficeUser(DirectorateOfficeUser $directorateOfficeUser): self
    {
        if (!$this->directorateOfficeUsers->contains($directorateOfficeUser)) {
            $this->directorateOfficeUsers[] = $directorateOfficeUser;
            $directorateOfficeUser->setDirectorate($this);
        }

        return $this;
    }

    public function removeDirectorateOfficeUser(DirectorateOfficeUser $directorateOfficeUser): self
    {
        if ($this->directorateOfficeUsers->removeElement($directorateOfficeUser)) {
            // set the owning side to null (unless already changed)
            if ($directorateOfficeUser->getDirectorate() === $this) {
                $directorateOfficeUser->setDirectorate(null);
            }
        }

        return $this;
    }

    public function getLastLoginAgo()
    {

        if ($this->lastLogin)
            return $this->getTimeElapsed($this->lastLogin->format('Y-m-d H:i:s'));
        return "hasn't signed in";
    }
    public function getTimeElapsed($datetime, $full = false)
    {
        $now = new \DateTime;
        $ago = new \DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    function __toString()
    {
  
          return "".$this->userInfo;
        //   return "".$this->userInfo.'->'.$this->email;

    }
  
    

 

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }
#    public function getUsername(): string
 #   {
 #       return (string) $this->username;
  //  }

    public function setUsername(string $username): self
    {
      $this->username = $username;
      return $this;
 }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getEmail(): string
    {
        return (string) $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }


    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles; 
        $roles[] = 'ROLE_USER'; 

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
       
        $this->roles = $roles;

        return $this;
    }
    public function addRole(string $role): self
    {
        $roles = $this->roles; 
        $roles[] = $role; 

       
        $this->setRoles(array_unique($roles));
        

        return $this;
    }
    public function removeRole(string $role): self
    {
        $roles = $this->roles; 
       
       
        $index = array_search($role, $roles);

        if($index !== false){
           unset($roles[$index]);  
        };
       
        $this->setRoles($roles);
        

        return $this;
    }

 

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }


        /**
     * Add permission.
     *
     * @param \App\Entity\Permission $permission
     *
     * @return User
     */
    public function addPermission(Permission $permission)
    {
        $this->permissions[] = $permission;

        return $this;
    }
    
 
    /**
     * Remove permission.
     *
     * @param \App\Entity\Permission  $permission
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePermission(Permission $permission)
    {
        return $this->permissions->removeElement($permission);
    }

    /**
     * Get permissions.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPermissions()
    {
        return $this->permissions;
    }
  

   

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection|Proposal[]
     */
    public function getProposals(): Collection
    {
        return $this->proposals;
    }

    public function addProposal(Proposal $proposal): self
    {
        if (!$this->proposals->contains($proposal)) {
            $this->proposals[] = $proposal;
            $proposal->setAuthor($this);
        }

        return $this;
    }

    public function removeProposal(Proposal $proposal): self
    {
        if ($this->proposals->removeElement($proposal)) {
            // set the owning side to null (unless already changed)
            if ($proposal->getAuthor() === $this) {
                $proposal->setAuthor(null);
            }
        }

        return $this;
    }
 
   
 

    /**
     * @return Collection|ReviewAssignment[]
     */
    public function getReviewAssignments(): Collection
    {
        return $this->reviewAssignments;
    }

    public function addReviewAssignment(ReviewAssignment $reviewAssignment): self
    {
        if (!$this->reviewAssignments->contains($reviewAssignment)) {
            $this->reviewAssignments[] = $reviewAssignment;
            $reviewAssignment->setReviewer($this);
        }

        return $this;
    }

    public function removeReviewAssignment(ReviewAssignment $reviewAssignment): self
    {
        if ($this->reviewAssignments->removeElement($reviewAssignment)) {
            // set the owning side to null (unless already changed)
            if ($reviewAssignment->getReviewer() === $this) {
                $reviewAssignment->setReviewer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|iRBReviewAssignments[]
     */
    public function getIRBReviewAssignments(): Collection
    {
        return $this->iRBReviewAssignments;
    }

    public function addIRBReviewAssignment(\App\Entity\IRB\IRBReviewAssignment $iRBReviewAssignments): self
    {
        if (!$this->iRBReviewAssignments->contains($iRBReviewAssignments)) {
            $this->iRBReviewAssignments[] = $iRBReviewAssignments;
            $iRBReviewAssignments->setIRBReviewer($this);
        }

        return $this;
    }

    public function removeIRBReviewAssignment(\App\Entity\IRB\IRBReviewAssignment $iRBReviewAssignments): self
    {
        if ($this->iRBReviewAssignments->removeElement($iRBReviewAssignments)) {
            // set the owning side to null (unless already changed)
            if ($iRBReviewAssignments->getIRBReviewer() === $this) {
                $iRBReviewAssignments->setIRBReviewer(null);
            }
        }

        return $this;
    }

    

    /**
     * @return Collection|EditorialDecision[]
     */
    public function getEditorialDecisions(): Collection
    {
        return $this->editorialDecisions;
    }
    

      /**
     * @return Collection|Submission[]
     */
    public function getSubmissions(): Collection
    {
        return $this->submissions;
    }
     
    

    public function addEditorialDecision(EditorialDecision $editorialDecision): self
    {
        if (!$this->editorialDecisions->contains($editorialDecision)) {
            $this->editorialDecisions[] = $editorialDecision;
            $editorialDecision->setEditedBy($this);
        }

        return $this;
    }

    public function removeEditorialDecision(EditorialDecision $editorialDecision): self
    {
        if ($this->editorialDecisions->removeElement($editorialDecision)) {
            // set the owning side to null (unless already changed)
            if ($editorialDecision->getEditedBy() === $this) {
                $editorialDecision->setEditedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CallForProposal[]
     */
    public function getCallForProposals(): Collection
    {
        return $this->callForProposals;
    }

    public function addCallForProposal(CallForProposal $callForProposal): self
    {
        if (!$this->callForProposals->contains($callForProposal)) {
            $this->callForProposals[] = $callForProposal;
            $callForProposal->setApprovedBy($this);
        }

        return $this;
    }

    public function removeCallForProposal(CallForProposal $callForProposal): self
    {
        if ($this->callForProposals->removeElement($callForProposal)) {
            // set the owning side to null (unless already changed)
            if ($callForProposal->getApprovedBy() === $this) {
                $callForProposal->setApprovedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Subscription[]
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(Subscription $subscription): self
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions[] = $subscription;
            $subscription->setUser($this);
        }

        return $this;
    }

    public function removeSubscription(Subscription $subscription): self
    {
        if ($this->subscriptions->removeElement($subscription)) {
            // set the owning side to null (unless already changed)
            if ($subscription->getUser() === $this) {
                $subscription->setUser(null);
            }
        }

        return $this;
    }
    
    
    /**
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setReviewedBy($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getReviewedBy() === $this) {
                $review->setReviewedBy(null);
            }
        }

        return $this;
    }
     /**
     * @return Collection<int,  IrbCertificate>
     */
    public function getIrbCertificates(): Collection
    {
        return $this->certs;
    }


    public function addIrbCertificate(IrbCertificate $cert): self
    {
        if (!$this->certs->contains($cert)) {
            $this->certs[] = $cert;
            $cert->setApprovedBy($this);
        }

        return $this;
    }

    public function removeIrbCertificate(?IrbCertificate $cert): self
    {
        if ($this->certs->removeElement($cert)) {
            // set the owning side to null (unless already changed)
            if ($cert->getApprovedBy() === $this) {
                $cert->setApprovedBy(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|Announcement[]
     */
    public function getAnnouncements(): Collection
    {
        return $this->announcements;
    }

    public function addAnnouncement(Announcement $announcement): self
    {
        if (!$this->announcements->contains($announcement)) {
            $this->announcements[] = $announcement;
            $announcement->setPostedBy($this);
        }

        return $this;
    }

    public function removeAnnouncement(Announcement $announcement): self
    {
        if ($this->announcements->removeElement($announcement)) {
            // set the owning side to null (unless already changed)
            if ($announcement->getPostedBy() === $this) {
                $announcement->setPostedBy(null);
            }
        }

        return $this;
    }
     /**
     * @return Collection|UserGroup[]
     */
    public function getUserGroup(): Collection
    {
        return $this->userGroup;
    }

    public function addUserGroup(UserGroup $userGroup): self
    {
        if (!$this->userGroup->contains($userGroup)) {
            $this->userGroup[] = $userGroup;
        }

        return $this;
    }

    public function removeUserGroup(UserGroup $userGroup): self
    {
        $this->userGroup->removeElement($userGroup);

        return $this;
    }

    public function getIsSuperAdmin(): ?bool
    {
        return $this->isSuperAdmin;
    }

    public function setIsSuperAdmin(bool $isSuperAdmin): self
    {
        $this->isSuperAdmin = $isSuperAdmin;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeInterface $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getRegisteredAt(): ?\DateTimeInterface
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(\DateTimeInterface $registeredAt): self
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    public function getUserInfo(): ?UserInfo
    {
        return $this->userInfo;
    }

    public function setUserInfo(UserInfo $userInfo): self
    {
        // set the owning side of the relation if necessary
        if ($userInfo->getUser() !== $this) {
            $userInfo->setUser($this);
        }

        $this->userInfo = $userInfo;

        return $this;
    }

    /**
     * @return Collection|CoAuthor[]
     */
    public function getCoAuthors(): Collection
    {
        return $this->coAuthors;
    }

    public function addCoAuthor(CoAuthor $coAuthor): self
    {
        if (!$this->coAuthors->contains($coAuthor)) {
            $this->coAuthors[] = $coAuthor;
            $coAuthor->setResearcher($this);
        }

        return $this;
    }

    public function removeCoAuthor(CoAuthor $coAuthor): self
    {
        if ($this->coAuthors->removeElement($coAuthor)) {
            // set the owning side to null (unless already changed)
            if ($coAuthor->getResearcher() === $this) {
                $coAuthor->setResearcher(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserFeedback[]
     */
    public function getUserFeedback(): Collection
    {
        return $this->userFeedback;
    }

    public function addUserFeedback(UserFeedback $userFeedback): self
    {
        if (!$this->userFeedback->contains($userFeedback)) {
            $this->userFeedback[] = $userFeedback;
            $userFeedback->setUser($this);
        }

        return $this;
    }

    public function removeUserFeedback(UserFeedback $userFeedback): self
    {
        if ($this->userFeedback->removeElement($userFeedback)) {
            // set the owning side to null (unless already changed)
            if ($userFeedback->getUser() === $this) {
                $userFeedback->setUser(null);
            }
        }

        return $this;
    }

    public function getIsReviewer(): ?bool
    {
        return $this->is_reviewer;
    }

    public function setIsReviewer(?bool $is_reviewer): self
    {
        $this->is_reviewer = $is_reviewer;

        return $this;
    }

    /**
     * @return Collection|TrainingParticipant[]
     */
    public function getTrainingParticipants(): Collection
    {
        return $this->trainingParticipants;
    }

    public function addTrainingParticipant(TrainingParticipant $trainingParticipant): self
    {
        if (!$this->trainingParticipants->contains($trainingParticipant)) {
            $this->trainingParticipants[] = $trainingParticipant;
            $trainingParticipant->setParticipant($this);
        }

        return $this;
    }

    public function removeTrainingParticipant(TrainingParticipant $trainingParticipant): self
    {
        if ($this->trainingParticipants->removeElement($trainingParticipant)) {
            // set the owning side to null (unless already changed)
            if ($trainingParticipant->getParticipant() === $this) {
                $trainingParticipant->setParticipant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Publication[]
     */
    public function getPublications(): Collection
    {
        return $this->publications;
    }

    public function addPublication(Publication $publication): self
    {
        if (!$this->publications->contains($publication)) {
            $this->publications[] = $publication;
            $publication->setAuthor($this);
        }

        return $this;
    }

    public function removePublication(Publication $publication): self
    {
        if ($this->publications->removeElement($publication)) {
            // set the owning side to null (unless already changed)
            if ($publication->getAuthor() === $this) {
                $publication->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Chat>
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): self
    {
        if (!$this->chats->contains($chat)) {
            $this->chats[] = $chat;
            $chat->setSentFrom($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): self
    {
        if ($this->chats->removeElement($chat)) {
            // set the owning side to null (unless already changed)
            if ($chat->getSentFrom() === $this) {
                $chat->setSentFrom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Chat>
     */
    public function getChatsTos(): Collection
    {
        return $this->chatsTos;
    }

    public function addChatsTo(Chat $chatsTo): self
    {
        if (!$this->chatsTos->contains($chatsTo)) {
            $this->chatsTos[] = $chatsTo;
            $chatsTo->setSentTo($this);
        }

        return $this;
    }

    public function removeChatsTo(Chat $chatsTo): self
    {
        if ($this->chatsTos->removeElement($chatsTo)) {
            // set the owning side to null (unless already changed)
            if ($chatsTo->getSentTo() === $this) {
                $chatsTo->setSentTo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ApplicationFeedback>
     */
    public function getApplicationFeedback(): Collection
    {
        return $this->applicationFeedback;
    }

    public function addApplicationFeedback(\App\Entity\IRB\ApplicationFeedback $applicationFeedback): self
    {
        if (!$this->applicationFeedback->contains($applicationFeedback)) {
            $this->applicationFeedback[] = $applicationFeedback;
            $applicationFeedback->setFeedbackFrom($this);
        }

        return $this;
    }

    public function removeApplicationFeedback(\App\Entity\IRB\ApplicationFeedback $applicationFeedback): self
    {
        if ($this->applicationFeedback->removeElement($applicationFeedback)) {
            // set the owning side to null (unless already changed)
            if ($applicationFeedback->getFeedbackFrom() === $this) {
                $applicationFeedback->setFeedbackFrom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CollaborativeResearchProject>
     */
    public function getCollaborativeResearchProjects(): Collection
    {
        return $this->collaborativeResearchProjects;
    }

    public function addCollaborativeResearchProject(\App\Entity\CRP\CollaborativeResearchProject $collaborativeResearchProject): self
    {
        if (!$this->collaborativeResearchProjects->contains($collaborativeResearchProject)) {
            $this->collaborativeResearchProjects[] = $collaborativeResearchProject;
            $collaborativeResearchProject->setPrincipalInvestigator($this);
        }

        return $this;
    }

    public function removeCollaborativeResearchProject(\App\Entity\CRP\CollaborativeResearchProject $collaborativeResearchProject): self
    {
        if ($this->collaborativeResearchProjects->removeElement($collaborativeResearchProject)) {
            // set the owning side to null (unless already changed)
            if ($collaborativeResearchProject->getPrincipalInvestigator() === $this) {
                $collaborativeResearchProject->setPrincipalInvestigator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CollaborativeResearchProject>
     */
    public function getCollaborativeResearchCoAuthorships(): Collection
    {
        return $this->collaborativeResearchCoAuthorships;
    }

    public function addCollaborativeResearchCoAuthorship(CollaborativeResearchProject $collaborativeResearchCoAuthorship): self
    {
        if (!$this->collaborativeResearchCoAuthorships->contains($collaborativeResearchCoAuthorship)) {
            $this->collaborativeResearchCoAuthorships[] = $collaborativeResearchCoAuthorship;
            $collaborativeResearchCoAuthorship->addCoInvestigator($this);
        }

        return $this;
    }

    public function removeCollaborativeResearchCoAuthorship(CollaborativeResearchProject $collaborativeResearchCoAuthorship): self
    {
        if ($this->collaborativeResearchCoAuthorships->removeElement($collaborativeResearchCoAuthorship)) {
            $collaborativeResearchCoAuthorship->removeCoInvestigator($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Deliverables>
     */
    public function getDeliverables(): Collection
    {
        return $this->deliverables;
    }

    public function addDeliverable(Deliverables $deliverable): self
    {
        if (!$this->deliverables->contains($deliverable)) {
            $this->deliverables[] = $deliverable;
            $deliverable->setResponsibleBody($this);
        }

        return $this;
    }

    public function removeDeliverable(Deliverables $deliverable): self
    {
        if ($this->deliverables->removeElement($deliverable)) {
            // set the owning side to null (unless already changed)
            if ($deliverable->getResponsibleBody() === $this) {
                $deliverable->setResponsibleBody(null);
            }
        }

        return $this;
    }

    
 

     

  
}
