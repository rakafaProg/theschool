<?php

// Here we handle the templates for each object type in the system

  class Card {
    private $templateURL;

    function __construct() {
      $this->templateURL = __DIR__.'/card.php';
    }


    function getAdminCardView ($admin, $user) {
      if($admin->getRole() < $user->getRole())
        return;

      $params = $this->buildBasicCard ($admin);

      $params['meta'] = $admin->getRoleName();
      $params['description'] =  [
         'Phone Number: <a href="tel:'.$admin->getPhone().'">'.$admin->getPhone().'</a>',
         'Eamil Adress: <a href="mailto:'.$admin->getEmail().'">'.$admin->getEmail().'</a>'
      ];

      $params['buttons'][] = [
          'color' => 'green',
          'href' => 'admin.php?edit='.$admin->getId().'#editingArea',
          'text' => 'Edit'
      ];

      if ($admin->getId() != $user->getId())
          $params['buttons'][] = [
              'color' => 'red',
              'href' => 'admin.php?delete='.$admin->getId(),
              'text' => 'Delete'
          ];

      include $this->templateURL;

    }

    function getCourseCardView ($course) {

      $params = $this->buildBasicCard ($course);

      $params['meta'] = $course->getStudentsCount().' students in this course';
      $params['description'] =  [nl2br ( $course->getDescription())];

      $params['buttons'][] = [
          'color' => 'green',
          'href' => 'school.php?viewcourse='.$course->getId().'#editingArea',
          'text' => 'View Details'
      ];

      include $this->templateURL;

    }

    function getStudentCardView ($student) {

      $params = $this->buildBasicCard ($student);

      $params['meta'] = 'Listed to '.$student->getCourseAmount().' courses';
      $params['description'] =  [
        'Phone Number: <a href="tel:'.$student->getPhone().'">'.$student->getPhone().'</a>',
        'Eamil Adress: <a href="mailto:'.$student->getEmail().'">'.$student->getEmail().'</a>'
      ];

      $params['buttons'][] = [
          'color' => 'green',
          'href' => 'school.php?viewstudent='.$student->getId().'#editingArea',
          'text' => 'View Details'
      ];

      include $this->templateURL;

    }


    private function buildBasicCard ($obj) {
      $params = [
        'image-url' => $obj->getImageURL(),
        'name' => $obj->getName(),
        'buttons' => []
      ];

      return $params;
    }



  }





  class Form {
    private $templateURL;

    function __construct() {
      $this->templateURL = __DIR__.'/form.php';
    }

    private function createInput ($type, $placeholder, $name, $value, $icon) {
      return [
        'type' => $type,
        'placeholder' => $placeholder,
        'name' => $name,
        'value' => $value,
        'icon' => $icon
      ];
    }

    public function createAdminEditForm($admin, $user) {
      if ($user->getRole() > $admin->getRole())
        return;
      // build basic params:
      $params = [
        'id' => $admin->getId(),
        'header'=> "Edit admin's details",
        'inputs' => [],
        'imageUrl' => $admin->getImageURL(),
        'imageName' => $admin->getImage()
      ];

      // set roles chooser - if Allowed
      if ($user->getId() != $admin->getId()) {
        $params['dropdwon'] = [
          'name' => 'role',
          'icon' => 'privacy',
          'options' => [
            ['value'=>2, 'extra' => $admin->getRole()==2 ? "selected" : '', 'text'=>'Manager'],
            ['value'=>3, 'extra' => $admin->getRole()==3 ? "selected" : '', 'text'=>'Sales']
          ]
        ];
      }

      // set basic inputs:
      $params['inputs'][] = $this->createInput('text', 'Name', 'name', $admin->getName(), 'user');
      $params['inputs'][] = $this->createInput('email', 'E-mail Address', 'email', $admin->getEmail(), 'mail');
      $params['inputs'][] = $this->createInput('text', 'Phone Number', 'phone', $admin->getPhone(), 'call');

      // set password if Allowed
      if ($user->getId() == $admin->getId()) {
        $params['inputs'][] = $this->createInput('password', 'Password', 'password', '', 'lock');
        $params['inputs'][] = $this->createInput('password', 'Repeate Password', 'repeat-password', '', 'lock');
      }

      include $this->templateURL;
    }


    public function createNewAdminForm() {

      // build basic params:
      $params = [
        'id' => -1,
        'header'=> "Create a New Administrator",
        'inputs' => [],
        'imageUrl' => '../images-uploading/users-profile/default.png',
        'imageName' => 'default.png'
      ];

      $params['dropdwon'] = [
           'name' => 'role',
           'placeholder' => 'Select Role',
           'icon' => 'privacy',
           'options' => [
             ['value'=>2, 'extra' => '', 'text'=>'Manager'],
             ['value'=>3, 'extra' => '', 'text'=>'Sales'],
           ]
      ];

      // set basic inputs:
      $params['inputs'][] = $this->createInput('text', 'Name', 'name', '', 'user');
      $params['inputs'][] = $this->createInput('email', 'E-mail Address', 'email', '', 'mail');
      $params['inputs'][] = $this->createInput('text', 'Phone Number', 'phone', '', 'call');
      $params['inputs'][] = $this->createInput('password', 'Password', 'password', '', 'lock');
      $params['inputs'][] = $this->createInput('password', 'Repeate Password', 'repeat-password', '', 'lock');

      include $this->templateURL;
    }




    public function createNewStudentForm($coursesList) {

      // build basic params:
      $params = [
        'id' => -1,
        'header'=> "Create a New Student",
        'inputs' => [],
        'imageUrl' => '../images-uploading/students-profile/default.png',
        'imageName' => 'default.png',
        'aditional' => $this->buildCourses($coursesList, -1)

      ];

      // set basic inputs:
      $params['inputs'][] = $this->createInput('text', 'Name', 'name', '', 'user');
      $params['inputs'][] = $this->createInput('email', 'E-mail Address', 'email', '', 'mail');
      $params['inputs'][] = $this->createInput('text', 'Phone Number', 'phone', '', 'call');

      include $this->templateURL;
    }

    public function editStudentForm($coursesList, $student) {

      // build basic params:
      $params = [
        'id' => $student->getId(),
        'header'=> "Edit Student",
        'inputs' => [],
        'imageUrl' => $student->getImageURL(),
        'imageName' => $student->getImage(),
        'aditional' => $this->buildCourses($coursesList, $student->getId()),
        'deletable' => 'school.php?delstd='.$student->getId()
      ];

      // set basic inputs:
      $params['inputs'][] = $this->createInput('text', 'Name', 'name', $student->getName(), 'user');
      $params['inputs'][] = $this->createInput('email', 'E-mail Address', 'email', $student->getEmail(), 'mail');
      $params['inputs'][] = $this->createInput('text', 'Phone Number', 'phone', $student->getPhone(), 'call');

      include $this->templateURL;
    }



    public function createNewCourseForm() {

      // build basic params:
      $params = [
        'id' => -1,
        'header'=> "Create a New Course",
        'inputs' => [$this->createInput('text', 'Name', 'name', '', 'student')],
        'textarea' => [
          'placeholder' => 'Description',
          'name' => 'description',
          'value' => ''
        ],
        'imageUrl' => '../images-uploading/courses/default.png',
        'imageName' => 'default.png'
      ];


      include $this->templateURL;
    }

    public function editCourseForm($course) {

      // build basic params:
      $params = [
        'id' => $course->getId(),
        'header'=> "Edit Course Details",
        'inputs' => [$this->createInput('text', 'Name', 'name', $course->getName(), 'student')],
        'textarea' => [
          'placeholder' => 'Description',
          'name' => 'description',
          'value' => $course->getDescription()
        ],
        'imageUrl' => $course->getImageURL(),
        'imageName' => $course->getImage()
      ];

      $params['aditional'] = "<div class='ui divider'></div><div class='ui header green'>";
      if ($course->getStudentsCount() == 0) {
        $params['deletable'] = 'school.php?delcrs='.$course->getId();
        $params['aditional'] .= "There are no students in this course</div>";
      } else {
        $params['aditional'] .= "There are ".$course->getStudentsCount()." students in this course</div>";
      }


      include $this->templateURL;
    }


    private function buildCourses($coursesList, $stdId) {
      $aditional = '<div class="ui header green">Courses: </div>
        <div class="ui grid">


      ';

      foreach ($coursesList as $course) {
        $aditional .= '

        <div class="four wide column">

        <label>
        <input
        class="ui checkbox"
        type="checkbox"
        name="course-'.$course->getId().'"';

        $aditional .= $course->getStudentId() == $stdId ? 'checked' : '';

        $aditional .= '>'.$course->getName().'</label>
        </div>

        ';
      }
      $aditional .= '</div>';


      return $aditional;

    }





  }



?>
