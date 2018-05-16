<?php
//Ban form
    if (isset($_POST['permanent'])) {
        $sql ="INSERT INTO banned (user_id,comp_ip,type,created) VALUES 
        ({$id},'{$user_details->comp_ip}',0,NOW())";

        if($db->query($sql)){
            if ($db->affected_rows==0) {
                $banError = "Could not ban user at this time";
            }else{
                header("Location: newsfeed.php");
            }
        }else{
            $banError = "Could not ban user at this time";
        }
    }

    if (isset($_POST['temp'])) {
        if ($_POST['day']!=0 && $_POST['month']!=0 && !empty($_POST['year'])) {
            $date = secure($_POST['year']).'-'.$_POST['month'].'-'.$_POST['day'];

            $sql ="INSERT INTO banned (user_id,comp_ip,type,ban_lift,created) VALUES 
            ({$id},'{$user_details->comp_ip}',1,'{$date}',NOW())";

            if($db->query($sql)){
                if ($db->affected_rows==0) {
                    $banError = "Could not ban user at this time";
                }else{
                    header("Location: newsfeed.php");
                }
            }else{
                $banError = "Could not ban user at this time".$date;
            }
        }else{
            $banError = "Please fill out date of ban lift";
        }
    }
        
             
    //edit profile pic
    if (isset($_POST['profPic'])) {
            if (!empty($_FILES['profPic']['name'])) {

                if ($_FILES['profPic']['error']!==0){
                    $errors[0]='There is an error with this file.';
                    
                }else{
                    $allowed_type=["image/jpeg","image/jpg","image/png"];
                    $allowed_ext=["jpeg","jpg","png"];
                    $allowed_size=4194304;

                    $name=$_FILES['profPic']['name'];
                    $tmp_name=$_FILES['profPic']['tmp_name'];
                    $size=$_FILES['profPic']['size'];
                    $type=$_FILES['profPic']['type'];
                    $error=$_FILES['profPic']['error'];

                    $ext=strtolower(end(explode('.', $name)));  
                    $filename=md5_file($tmp_name).time().'.'.$ext;
                    $location='images/'.$filename;

                    if (!in_array($type, $allowed_type) || !in_array($ext, $allowed_ext) || $size>$allowed_size) {
                        $errors[0]='Image must be less than 4MB & be a JPG, JPEG or PNG Image.';
                    }
                }   
            }else{
                $filename="default_pic.png";
            }

            if(empty($errors)){

                if (!empty($_FILES['profPic']['name']) && move_uploaded_file($tmp_name, $location)==false) {
                        $errors[0]='Image cannot be uploaded at this time.';
                }else{
                    $sql="UPDATE profile_details SET profile_pic='{$filename}' WHERE user_id={$id}";
                    $db->query($sql);
                    header("Location: ".$url);
                }
            }
    }

    //edit cover pic
    if (isset($_POST['coverPic'])) {
            if (!empty($_FILES['coverPic']['name'])) {

                if ($_FILES['coverPic']['error']!==0){
                    $errors[1]='There is an error with this file.';
                    
                }else{
                    $allowed_type=["image/jpeg","image/jpg","image/png"];
                    $allowed_ext=["jpeg","jpg","png"];
                    $allowed_size=4194304;

                    $name=$_FILES['coverPic']['name'];
                    $tmp_name=$_FILES['coverPic']['tmp_name'];
                    $size=$_FILES['coverPic']['size'];
                    $type=$_FILES['coverPic']['type'];
                    $error=$_FILES['coverPic']['error'];

                    $ext=strtolower(end(explode('.', $name)));  
                    $filename=md5_file($tmp_name).time().'.'.$ext;
                    $location='images/'.$filename;

                    if (!in_array($type, $allowed_type) || !in_array($ext, $allowed_ext) || $size>$allowed_size) {
                        $errors[1]='Image must be less than 4MB & be a JPG, JPEG or PNG Image.';
                    }
                }   
            }else{
                $filename="cover.png";
            }

            if(empty($errors)){

                if (!empty($_FILES['coverPic']['name']) && move_uploaded_file($tmp_name, $location)==false) {
                        $errors[1]='Image cannot be uploaded at this time.';
                }else{
                    $sql="UPDATE profile_details SET cover_pic='{$filename}' WHERE user_id={$id}";
                    $db->query($sql);
                    header("Location: ".$url);
                }
            }
    }

    //edit skills
    if (isset($_POST['editSkill'])) {
            if(isset($_POST['skill'])){
                $skill = $_POST['skill'] ;
                if (sizeof($skill)===0) {
                    $errors[2]='Please pick at least one Skill ';
                }
            }else{
                $errors[2]='Please pick at least one Skill ';
            }

        if(empty($errors)){
            
            $db->query("DELETE FROM user_skills WHERE user_id={$id}");
            foreach( $skill as $key => $value) {
                $db->query("INSERT INTO user_skills (user_id,skill_id)  VALUES ( {$id},{$value})");
            }
            header("Location: ".$url);
        }   
    }

     //edit company about description
    if (isset($_POST['orgAboutSubmit'])) {
        $orgAbout = secure($_POST['orgAbout']);

        if (strlen($orgAbout)<1) {
            $errors[3]='Please enter some details about your Company';
        }

         if(empty($errors)){
             $sql="UPDATE profile_details SET about='{$orgAbout}' WHERE user_id={$id}";
            $db->query($sql);
            header("Location: ".$url);
        } 
    }

     //edit company employee description
    if (isset($_POST['orgEmpSubmit'])) {
        $orgEmp = secure($_POST['orgEmp']);

        if (strlen($orgEmp)<1) {
            $errors[4]='Please enter some details about the employee your company looks for';
        }

         if(empty($errors)){
             $sql="UPDATE company_details SET emp_desc='{$orgEmp}' WHERE user_id={$id}";
            $db->query($sql);
            header("Location: ".$url);
        } 
    }

    //edit about description
    if (isset($_POST['aboutSubmit'])) {
        $aboutDesc = secure($_POST['aboutDesc']);

        if (strlen($aboutDesc)<1) {
            $errors[3]='Please enter some details about yourself';
        }

         if(empty($errors)){
             $sql="UPDATE profile_details SET about='{$aboutDesc}' WHERE user_id={$id}";
            $db->query($sql);
            header("Location: ".$url);
        } 
    }

    //edit qualifications
    if (isset($_POST['qualSubmit'])) {
        
        $qualTitle = nameFix(secure($_POST['qualTitle']));
        $qualLevel = nameFix(secure($_POST['qualLevel']));
        $qualDay = $_POST['qualDay'];
        $qualMonth = $_POST['qualMonth'];
        $qualYear = secure($_POST['qualYear']);
        $qualDate = $qualYear.'-'.$qualMonth.'-'.$qualDay;
        $qualDesc = secure($_POST['qualDesc']);

        if (strlen($qualTitle)<1) {
            $errors[4]='Please enter the title of your Qualification';
        }else if (strlen($qualLevel)<1) {
            $errors[4]='Please enter the Level of your Qualification';
        }else if ($qualDay === 0 || $qualMonth === 0) {
            $errors[4]='Please enter a correct end Date';
        }else if ( $qualYear<1900 || $qualYear>2018) {
            $errors[4]='Please enter the date you obtained your Qualification';
        }else if (strlen($qualDesc)<1) {
            $errors[4]='Please enter some details of your Qualification';
        }

        if(empty($errors)){
            $sql="UPDATE qualifications SET title='{$qualTitle}',description='{$qualDesc}',level='{$qualLevel}',obtained='{$qualDate}' WHERE user_id={$id}";
            $db->query($sql);
            header("Location: ".$url);
        }   
    }

    //edit employment
    if (isset($_POST['empSubmit'])) {


        $empCompany = nameFix(secure($_POST['empCompany']));
        $empTitle = nameFix(secure($_POST['empTitle']));
        $empDesc = secure($_POST['empDesc']);
        $fromEmpDay = $_POST['fromEmpDay'];
        $fromEmpMonth = $_POST['fromEmpMonth'];
        $fromEmpYear = secure($_POST['fromEmpYear']);
        $fromDate = $fromEmpYear.'-'.$fromEmpMonth.'-'.$fromEmpDay;
        $toEmpDay = $_POST['toEmpDay'];
        $toEmpMonth = $_POST['toEmpMonth'];
        $toEmpYear = secure($_POST['toEmpYear']);
        $toDate = $toEmpYear.'-'.$toEmpMonth.'-'.$toEmpDay;

        if (strlen($empCompany)<1) {
            $errors[5]='Please enter the Company';
        }else if (strlen($empTitle)<1) {
            $errors[5]='Please enter the title of your Role';
        }else if ($fromEmpDay === 0 || $fromEmpMonth === 0) {
            $errors[5]='Please enter a correct start Date';
        }else if ($fromEmpYear<1900 || $fromEmpYear>2018) {
            $errors[5]='Please enter a correct start Date';
        }else if ($toEmpDay === 0 || $toEmpMonth === 0) {
            $errors[5]='Please enter a correct end Date';
        }else if ( $toEmpYear<1900 || $toEmpYear>2018) {
            $errors[5]='Please enter a correct end Date';
        }else if ( $toDate<$fromDate) {
            $errors[5]='Invalid Dates';
        }else if (strlen($empDesc)<1) {
            $errors[5]='Please enter some details of your Role';
        }

        if(empty($errors)){
            $sql="UPDATE employment SET company='{$empCompany}',from_date='{$fromDate}',to_date='{$toDate}',title='{$empTitle}',description='{$empDesc}' WHERE user_id={$id}";
            $db->query($sql);
            header("Location: ".$url);
        }   

        
    }
    

?>

<div id="editProfile">
	<?php
		if(admin()){
	?>
		<form action="" method="POST" name="ban">
		
	<?php if (isset($banError) && !empty($banError)) { ?>
			<div class="warning"><?php echo $banError;?></div>
	<?php } ?>
		
		<div class="ban">
			<input type="submit" name="permanent" value="Permanent Ban">
			<span>OR     Ban until</span>
			<select  name="day">
                <option value="0">--</option>
                <option value="01">1</option>
                <option value="02">2</option>
                <option value="03">3</option>
                <option value="04">4</option>
                <option value="05">5</option>
                <option value="06">6</option>
                <option value="07">7</option>
                <option value="08">8</option>
                <option value="09">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
                <option value="13">13</option>
                <option value="14">14</option>
                <option value="15">15</option>
                <option value="16">16</option>
                <option value="17">17</option>
                <option value="18">18</option>
                <option value="19">19</option>
                <option value="20">20</option>
                <option value="21">21</option>
                <option value="22">22</option>
                <option value="23">23</option>
                <option value="24">24</option>
                <option value="25">25</option>
                <option value="26">26</option>
                <option value="27">27</option>
                <option value="28">28</option>
                <option value="29">29</option>
                <option value="30">30</option>
                <option value="31">31</option>
       		</select>
            <select  name="month" >
                <option value="0">--</option>
                <option value="01">1</option>
                <option value="02">2</option>
                <option value="03">3</option>
                <option value="04">4</option>
                <option value="05">5</option>
                <option value="06">6</option>
                <option value="07">7</option>
                <option value="08">8</option>
                <option value="09">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
            </select>
			<input type="number" name="year" placeholder="Year">

			<input type="submit" name="temp" value="Temp Ban">
		</div>
	</form>
<?php } ?>
    
        <form action="" method="POST" enctype="multipart/form-data"  class="sec">
            <h2>Upload a Profile Picture</h2>

            <?php if(isset($errors[0])){ ?>

                <div class="warning">
                    <?php echo $errors[0]; ?>
                </div>

             <?php } ?>
            <!--<label for="file">Choose Photo</label>-->
            <input type="file" name="profPic" >

            <input type="submit" name="profPic" value="Submit">
        </form>

        <form action="" method="POST" enctype="multipart/form-data"  class="sec">
            <h2>Upload a Cover Photo</h2>

            <?php if(isset($errors[1])){ ?>

                <div class="warning">
                    <?php echo $errors[1]; ?>
                </div>

             <?php } ?>
            <!-- <label for="file">Choose Photo</label>-->
           <input type="file" name="coverPic" >

            <input type="submit" name="coverPic" value="Submit">
        </form>

    <?php if($user_details->accType == 1){?>
        
        <!-- Org Skills -->
        <form action="" method="POST"  class="sec">
            <h2>Please identify skills your Company requires</h2>
            <?php if(isset($errors[2])){ ?>

                <div class="warning">
                    <?php echo $errors[2]; ?>
                </div>

             <?php } ?>
            <div class="skillSec">
                <?php 
                    $myskills = array();
                    if (!empty($prof_skills)){
                        foreach ($prof_skills as $value) {
                            $myskills[] = $value[0];
                        }
                    }
                    
                    $sql = "SELECT title FROM skills";  

                    if ($results=$db->query($sql)) {
                        //$skillSet=$results->fetch_all();
                        while ($row = $results->fetch_array(MYSQLI_BOTH)) {
                                    $skillSet[] = $row;
                                }
                        mysqli_free_result($results);
                    }

    
                    foreach ($skillSet as $key => $value) {
                        $checked=false;
                        if (in_array($value[0], $myskills)==true) {
                            $checked=true;
                        }
                        
                ?>      
                    <span>
                        <label><?php echo $value[0];?>:</label>
                        <input type="checkbox" name="skill[]" value="<?php echo $key+1;?>" <?php if($checked){ echo "checked"; }?>>
                    </span>
                <?php } ?>
            </div>
            <input type="submit" name="editSkill" value="Submit">
        </form>

        <!-- Org About -->
        <form action="" method="POST"  class="sec">
            <h2>Please describe your Organisation</h2>
            <?php if(isset($errors[3])){ ?>

                <div class="warning">
                    <?php echo $errors[3]; ?>
                </div>

             <?php } ?>
            <textarea name="orgAbout" placeholder="Describe type of Organisation"><?php if(isset($orgAbout)){echo $orgAbout;}else{echo $profile_details->about;}?></textarea>
            <input type="submit" name="orgAboutSubmit" value="Submit">
        </form>

        <!-- Org Employee -->
        <form action="" method="POST"  class="sec">
            <h2>Please outline what sort of employees you are looking for</h2>
            <?php if(isset($errors[4])){ ?>

                <div class="warning">
                    <?php echo $errors[4]; ?>
                </div>

             <?php } ?>
            <textarea name="orgEmp" placeholder="Describe type of Employee"><?php if(isset($orgEmp)){echo $orgEmp;}else if(isset($company_details)){echo $company_details->emp_desc;}?></textarea>
            <input type="submit" name="orgEmpSubmit" value="Submit">
        </form>

    <?php }else{?>

        <!-- User Skills -->
        <form action="" method="POST"  class="sec">
            <h2>Please identify your skills</h2>
            <?php if(isset($errors[2])){ ?>

                <div class="warning">
                    <?php echo $errors[2]; ?>
                </div>

             <?php } ?>
            <div class="skillSec">
                <?php 
                    $myskills = array();

                    if (!empty($prof_skills)){
                        foreach ($prof_skills as $value) {
                            $myskills[] = $value[0];
                        }
                    }

                    $sql = "SELECT title FROM skills";  

                    if ($results=$db->query($sql)) {
                        //$skillSet=$results->fetch_all();
                        while ($row = $results->fetch_array(MYSQLI_BOTH)) {
                                    $skillSet[] = $row;
                                }
                        mysqli_free_result($results);
                    }

    
                    foreach ($skillSet as $key => $value) {
                        $checked=false;
                        if (in_array($value[0], $myskills)==true) {
                            $checked=true;
                        }
                        
                ?>     
                    <span>
                        <label><?php echo $value[0];?>:</label>
                        <input type="checkbox" name="skill[]" value="<?php echo $key+1;?>" <?php if($checked){ echo "checked"; }?>>
                    </span>
                <?php } ?>
            </div>
            <input type="submit" name="editSkill" value="Submit">
        </form> 

        <!-- User About -->
        <form action="" method="POST"  class="sec">
            <h2>Please descibe yourself</h2>
            <?php if(isset($errors[3])){ ?>

                <div class="warning">
                    <?php echo $errors[3]; ?>
                </div>

             <?php } ?>
            <textarea name="aboutDesc" placeholder="About you"><?php if(isset($aboutDesc)){echo $aboutDesc;}else if(isset($profile_details)){
                    echo $profile_details->about;
                }?></textarea>
            <input type="submit" name="aboutSubmit" value="Submit">
        </form>

        <!-- User Qualifications -->
        <form action="" method="POST"  class="sec">
            <h2>Please outline your Qualifications</h2>
            <?php if(isset($errors[4])){ ?>

                <div class="warning">
                    <?php echo $errors[4]; ?>
                </div>

             <?php } ?>


            <div class="info"><label>Title of Qualification: </label><input type="text" name="qualTitle" placeholder="Title" value="<?php if(isset($qualTitle)){echo $qualTitle;}else{
                    echo $qualifications->title;
                }?>"></div>
             <div class="info"><label>Level of Qualification:</label><input type="text" name="qualLevel" placeholder="Level" value="<?php if(isset($qualLevel)){echo $qualLevel;}else{
                    echo $qualifications->level;
                }?>"></div>
            <div class="info"><label>Year you obtained Qualification:</label> 
                <select  name="qualDay">
                    <option value="0">--</option>
                        <option value="01" <?php if(isset($qualDay) && $qualDay==01){ echo "selected"; }else if($obtDayOrg == 01){echo "selected";}  ?>>1</option>
                        <option value="02" <?php if(isset($qualDay) && $qualDay==02){ echo "selected"; }else if($obtDayOrg == 02){echo "selected";}  ?>>2</option>
                        <option value="03" <?php if(isset($qualDay) && $qualDay==03){ echo "selected"; }else if($obtDayOrg == 03){echo "selected";}  ?>>3</option>
                        <option value="04" <?php if(isset($qualDay) && $qualDay==04){ echo "selected"; }else if($obtDayOrg == 04){echo "selected";}  ?>>4</option>
                        <option value="05" <?php if(isset($qualDay) && $qualDay==05){ echo "selected"; }else if($obtDayOrg == 05){echo "selected";}  ?>>5</option>
                        <option value="06" <?php if(isset($qualDay) && $qualDay==06){ echo "selected"; }else if($obtDayOrg == 06){echo "selected";}  ?>>6</option>
                        <option value="07" <?php if(isset($qualDay) && $qualDay==07){ echo "selected"; }else if($obtDayOrg == 07){echo "selected";}  ?>>7</option>
                        <option value="08" <?php if(isset($qualDay) && $qualDay==8){ echo "selected"; }else if($obtDayOrg == 8){echo "selected";}  ?>>8</option>
                        <option value="09" <?php if(isset($qualDay) && $qualDay==9){ echo "selected"; }else if($obtDayOrg == 9){echo "selected";}  ?>>9</option>
                        <option value="10" <?php if(isset($qualDay) && $qualDay==10){ echo "selected"; }else if($obtDayOrg == 10){echo "selected";}  ?>>10</option>
                        <option value="11" <?php if(isset($qualDay) && $qualDay==11){ echo "selected"; }else if($obtDayOrg == 11){echo "selected";}  ?>>11</option>
                        <option value="12" <?php if(isset($qualDay) && $qualDay==12){ echo "selected"; }else if($obtDayOrg == 12){echo "selected";}  ?>>12</option>
                        <option value="13" <?php if(isset($qualDay) && $qualDay==13){ echo "selected"; }else if($obtDayOrg == 13){echo "selected";}  ?>>13</option>
                        <option value="14" <?php if(isset($qualDay) && $qualDay==14){ echo "selected"; }else if($obtDayOrg == 14){echo "selected";}  ?>>14</option>
                        <option value="15" <?php if(isset($qualDay) && $qualDay==15){ echo "selected"; }else if($obtDayOrg == 15){echo "selected";}  ?>>15</option>
                        <option value="16" <?php if(isset($qualDay) && $qualDay==16){ echo "selected"; }else if($obtDayOrg == 16){echo "selected";}  ?>>16</option>
                        <option value="17" <?php if(isset($qualDay) && $qualDay==17){ echo "selected"; }else if($obtDayOrg == 17){echo "selected";}  ?>>17</option>
                        <option value="18" <?php if(isset($qualDay) && $qualDay==18){ echo "selected"; }else if($obtDayOrg == 18){echo "selected";}  ?>>18</option>
                        <option value="19" <?php if(isset($qualDay) && $qualDay==19){ echo "selected"; }else if($obtDayOrg == 19){echo "selected";}  ?>>19</option>
                        <option value="20" <?php if(isset($qualDay) && $qualDay==20){ echo "selected"; }else if($obtDayOrg == 20){echo "selected";}  ?>>20</option>
                        <option value="21" <?php if(isset($qualDay) && $qualDay==21){ echo "selected"; }else if($obtDayOrg == 21){echo "selected";}  ?>>21</option>
                        <option value="22" <?php if(isset($qualDay) && $qualDay==22){ echo "selected"; }else if($obtDayOrg == 22){echo "selected";}  ?>>22</option>
                        <option value="23" <?php if(isset($qualDay) && $qualDay==23){ echo "selected"; }else if($obtDayOrg == 23){echo "selected";}  ?>>23</option>
                        <option value="24" <?php if(isset($qualDay) && $qualDay==24){ echo "selected"; }else if($obtDayOrg == 24){echo "selected";}  ?>>24</option>
                        <option value="25" <?php if(isset($qualDay) && $qualDay==25){ echo "selected"; }else if($obtDayOrg == 25){echo "selected";}  ?>>25</option>
                        <option value="26" <?php if(isset($qualDay) && $qualDay==26){ echo "selected"; }else if($obtDayOrg == 26){echo "selected";}  ?>>26</option>
                        <option value="27" <?php if(isset($qualDay) && $qualDay==27){ echo "selected"; }else if($obtDayOrg == 27){echo "selected";}  ?>>27</option>
                        <option value="28" <?php if(isset($qualDay) && $qualDay==28){ echo "selected"; }else if($obtDayOrg == 28){echo "selected";}  ?>>28</option>
                        <option value="29" <?php if(isset($qualDay) && $qualDay==29){ echo "selected"; }else if($obtDayOrg == 29){echo "selected";}  ?>>29</option>
                        <option value="30" <?php if(isset($qualDay) && $qualDay==30){ echo "selected"; }else if($obtDayOrg == 30){echo "selected";}  ?>>30</option>
                        <option value="31" <?php if(isset($qualDay) && $qualDay==31){ echo "selected"; }else if($obtDayOrg == 31){echo "selected";}  ?>>31</option>
                </select>
                <select  name="qualMonth" >
                    <option value="0">--</option>
                    <option value="01" <?php if(isset($qualMonth) && $qualMonth==01){ echo "selected"; }else if($obtMonthOrg == 01){echo "selected";}  ?>>January</option>
                    <option value="02" <?php if(isset($qualMonth) && $qualMonth==02){ echo "selected"; }else if($obtMonthOrg == 02){echo "selected";}  ?>>February</option>
                    <option value="03" <?php if(isset($qualMonth) && $qualMonth==03){ echo "selected"; }else if($obtMonthOrg == 03){echo "selected";} ?>>March</option>
                    <option value="04" <?php if(isset($qualMonth) && $qualMonth==04){ echo "selected"; }else if($obtMonthOrg == 04){echo "selected";}  ?>>April</option>
                    <option value="05" <?php if(isset($qualMonth) && $qualMonth==05){ echo "selected"; }else if($obtMonthOrg == 05){echo "selected";}  ?>>May</option>
                    <option value="06" <?php if(isset($qualMonth) && $qualMonth==06){ echo "selected"; }else if($obtMonthOrg == 06){echo "selected";}  ?>>June</option>
                    <option value="07" <?php if(isset($qualMonth) && $qualMonth==07){ echo "selected"; }else if($obtMonthOrg == 07){echo "selected";}  ?>>July</option>
                    <option value="08" <?php if(isset($qualMonth) && $qualMonth==8){ echo "selected"; }else if($obtMonthOrg == 8){echo "selected";}  ?>>August</option>
                    <option value="09" <?php if(isset($qualMonth) && $qualMonth==9){ echo "selected"; }else if($obtMonthOrg == 9){echo "selected";}  ?>>September</option>
                    <option value="10" <?php if(isset($qualMonth) && $qualMonth==10){ echo "selected"; }else if($obtMonthOrg == 10){echo "selected";}  ?>>October</option>
                    <option value="11" <?php if(isset($qualMonth) && $qualMonth==11){ echo "selected"; }else if($obtMonthOrg == 11){echo "selected";}  ?>>November</option>
                    <option value="12" <?php if(isset($qualMonth) && $qualMonth==12){ echo "selected"; }else if($obtMonthOrg == 12){echo "selected";}  ?>>December</option>
                </select>
                <input type="number" name="qualYear" placeholder="Year" min="1950" max="2018" value="<?php if(isset($qualYear) ){ echo $qualYear; }else{echo $obtYearOrg;}  ?>">
            </div>
            <textarea name="qualDesc" placeholder="Description of Qualification"><?php 
            if(isset($qualDesc)){echo $qualDesc;}else{
                    echo $qualifications->description;
                }?></textarea>
            <input type="submit" name="qualSubmit" value="Submit">
        </form>

        <!-- User Employment -->
        <form action="" method="POST"  class="sec">
            <h2>Please List your Employment History</h2>
            <?php if(isset($errors[5])){ ?>

                <div class="warning">
                    <?php echo $errors[5]; ?>
                </div>

             <?php } ?>
             <div class="info"><label>Company: </label><input type="text" name="empCompany" placeholder="Company" value="<?php if(isset($empCompany)){echo $empCompany;}else{
                    echo $employment->company;
                }?>"></div>
             <div class="info"><label>Title of Job: </label><input type="text" name="empTitle" placeholder="Title" value="<?php if(isset($empTitle)){echo $empTitle;}else{
                    echo $employment->title;
                }?>"></div>
            <div class="info"><label>Started the role in:</label> 
                <select  name="fromEmpDay">
                        <option value="0" <?php if($fromDayOrg == 0){echo "selected";}?>>--</option>
                        <option value="01" <?php if(isset($fromEmpDay) && $fromEmpDay==01){ echo "selected"; }else if($fromDayOrg == 01){echo "selected";}  ?>>1</option>
                        <option value="02" <?php if(isset($fromEmpDay) && $fromEmpDay==02){ echo "selected"; }else if($fromDayOrg == 02){echo "selected";}  ?>>2</option>
                        <option value="03" <?php if(isset($fromEmpDay) && $fromEmpDay==03){ echo "selected"; }else if($fromDayOrg == 03){echo "selected";}  ?>>3</option>
                        <option value="04" <?php if(isset($fromEmpDay) && $fromEmpDay==04){ echo "selected"; }else if($fromDayOrg == 04){echo "selected";}  ?>>4</option>
                        <option value="05" <?php if(isset($fromEmpDay) && $fromEmpDay==05){ echo "selected"; }else if($fromDayOrg == 05){echo "selected";}  ?>>5</option>
                        <option value="06" <?php if(isset($fromEmpDay) && $fromEmpDay==06){ echo "selected"; }else if($fromDayOrg == 06){echo "selected";}  ?>>6</option>
                        <option value="07" <?php if(isset($fromEmpDay) && $fromEmpDay==07){ echo "selected"; }else if($fromDayOrg == 07){echo "selected";}  ?>>7</option>
                        <option value="08" <?php if(isset($fromEmpDay) && $fromEmpDay==8){ echo "selected"; }else if($fromDayOrg == 8){echo "selected";}  ?>>8</option>
                        <option value="09" <?php if(isset($fromEmpDay) && $fromEmpDay==9){ echo "selected"; }else if($fromDayOrg == 9){echo "selected";}  ?>>9</option>
                        <option value="10" <?php if(isset($fromEmpDay) && $fromEmpDay==10){ echo "selected"; }else if($fromDayOrg == 10){echo "selected";}  ?>>10</option>
                        <option value="11" <?php if(isset($fromEmpDay) && $fromEmpDay==11){ echo "selected"; }else if($fromDayOrg == 11){echo "selected";}  ?>>11</option>
                        <option value="12" <?php if(isset($fromEmpDay) && $fromEmpDay==12){ echo "selected"; }else if($fromDayOrg == 12){echo "selected";}  ?>>12</option>
                        <option value="13" <?php if(isset($fromEmpDay) && $fromEmpDay==13){ echo "selected"; }else if($fromDayOrg == 13){echo "selected";}  ?>>13</option>
                        <option value="14" <?php if(isset($fromEmpDay) && $fromEmpDay==14){ echo "selected"; }else if($fromDayOrg == 14){echo "selected";}  ?>>14</option>
                        <option value="15" <?php if(isset($fromEmpDay) && $fromEmpDay==15){ echo "selected"; }else if($fromDayOrg == 15){echo "selected";}  ?>>15</option>
                        <option value="16" <?php if(isset($fromEmpDay) && $fromEmpDay==16){ echo "selected"; }else if($fromDayOrg == 16){echo "selected";}  ?>>16</option>
                        <option value="17" <?php if(isset($fromEmpDay) && $fromEmpDay==17){ echo "selected"; }else if($fromDayOrg == 17){echo "selected";}  ?>>17</option>
                        <option value="18" <?php if(isset($fromEmpDay) && $fromEmpDay==18){ echo "selected"; }else if($fromDayOrg == 18){echo "selected";}  ?>>18</option>
                        <option value="19" <?php if(isset($fromEmpDay) && $fromEmpDay==19){ echo "selected"; }else if($fromDayOrg == 19){echo "selected";}  ?>>19</option>
                        <option value="20" <?php if(isset($fromEmpDay) && $fromEmpDay==20){ echo "selected"; }else if($fromDayOrg == 20){echo "selected";}  ?>>20</option>
                        <option value="21" <?php if(isset($fromEmpDay) && $fromEmpDay==21){ echo "selected"; }else if($fromDayOrg == 21){echo "selected";}  ?>>21</option>
                        <option value="22" <?php if(isset($fromEmpDay) && $fromEmpDay==22){ echo "selected"; }else if($fromDayOrg == 22){echo "selected";}  ?>>22</option>
                        <option value="23" <?php if(isset($fromEmpDay) && $fromEmpDay==23){ echo "selected"; }else if($fromDayOrg == 23){echo "selected";}  ?>>23</option>
                        <option value="24" <?php if(isset($fromEmpDay) && $fromEmpDay==24){ echo "selected"; }else if($fromDayOrg == 24){echo "selected";}  ?>>24</option>
                        <option value="25" <?php if(isset($fromEmpDay) && $fromEmpDay==25){ echo "selected"; }else if($fromDayOrg == 25){echo "selected";}  ?>>25</option>
                        <option value="26" <?php if(isset($fromEmpDay) && $fromEmpDay==26){ echo "selected"; }else if($fromDayOrg == 26){echo "selected";}  ?>>26</option>
                        <option value="27" <?php if(isset($fromEmpDay) && $fromEmpDay==27){ echo "selected"; }else if($fromDayOrg == 27){echo "selected";}  ?>>27</option>
                        <option value="28" <?php if(isset($fromEmpDay) && $fromEmpDay==28){ echo "selected"; }else if($fromDayOrg == 28){echo "selected";}  ?>>28</option>
                        <option value="29" <?php if(isset($fromEmpDay) && $fromEmpDay==29){ echo "selected"; }else if($fromDayOrg == 29){echo "selected";}  ?>>29</option>
                        <option value="30" <?php if(isset($fromEmpDay) && $fromEmpDay==30){ echo "selected"; }else if($fromDayOrg == 30){echo "selected";}  ?>>30</option>
                        <option value="31" <?php if(isset($fromEmpDay) && $fromEmpDay==31){ echo "selected"; }else if($fromDayOrg == 31){echo "selected";}  ?>>31</option>
                </select>
                <select  name="fromEmpMonth" >
                    <option value="0" <?php if($fromMonthOrg == 0){echo "selected";}?>>--</option>
                    <option value="01" <?php if(isset($fromEmpMonth) && $fromEmpMonth==01){ echo "selected"; }else if($fromMonthOrg == 01){echo "selected";}  ?>>January</option>
                    <option value="02" <?php if(isset($fromEmpMonth) && $fromEmpMonth==02){ echo "selected"; }else if($fromMonthOrg == 02){echo "selected";}  ?>>February</option>
                    <option value="03" <?php if(isset($fromEmpMonth) && $fromEmpMonth==03){ echo "selected"; }else if($fromMonthOrg == 03){echo "selected";}  ?>>March</option>
                    <option value="04" <?php if(isset($fromEmpMonth) && $fromEmpMonth==04){ echo "selected"; }else if($fromMonthOrg == 04){echo "selected";}  ?>>April</option>
                    <option value="05" <?php if(isset($fromEmpMonth) && $fromEmpMonth==05){ echo "selected"; }else if($fromMonthOrg == 05){echo "selected";}  ?>>May</option>
                    <option value="06" <?php if(isset($fromEmpMonth) && $fromEmpMonth==06){ echo "selected"; }else if($fromMonthOrg == 06){echo "selected";}  ?>>June</option>
                    <option value="07" <?php if(isset($fromEmpMonth) && $fromEmpMonth==07){ echo "selected"; }else if($fromMonthOrg == 07){echo "selected";} ?>>July</option>
                    <option value="08" <?php if(isset($fromEmpMonth) && $fromEmpMonth==8){ echo "selected"; }else if($fromMonthOrg == 8){echo "selected";}  ?>>August</option>
                    <option value="09" <?php if(isset($fromEmpMonth) && $fromEmpMonth==9){ echo "selected"; }else if($fromMonthOrg == 9){echo "selected";}  ?>>September</option>
                    <option value="10" <?php if(isset($fromEmpMonth) && $fromEmpMonth==10){ echo "selected"; }else if($fromMonthOrg == 10){echo "selected";}  ?>>October</option>
                    <option value="11" <?php if(isset($fromEmpMonth) && $fromEmpMonth==11){ echo "selected"; }else if($fromMonthOrg == 11){echo "selected";}  ?>>November</option>
                    <option value="12" <?php if(isset($fromEmpMonth) && $fromEmpMonth==12){ echo "selected"; }else if($fromMonthOrg == 12){echo "selected";}  ?>>December</option>
                </select>
                <input type="number" name="fromEmpYear" placeholder="Year" min="1950" max="2018" value="<?php if(isset($fromEmpYear) ){ echo $fromEmpYear; }else if(isset($fromYearOrg)){echo $fromYearOrg;}  ?>"></div>
            <div class="info"><label>Finished the role in:</label> 
                <select  name="toEmpDay">
                    <option value="0" <?php if($toDayOrg == 0){echo "selected";}?>>--</option>
                    <option value="01" <?php if(isset($toEmpDay) && $toEmpDay==01){ echo "selected"; }else if($toDayOrg == 01){echo "selected";} ?>>1</option>
                    <option value="02" <?php if(isset($toEmpDay) && $toEmpDay==02){ echo "selected"; }else if($toDayOrg == 02){echo "selected";} ?>>2</option>
                    <option value="03" <?php if(isset($toEmpDay) && $toEmpDay==03){ echo "selected"; }else if($toDayOrg == 03){echo "selected";} ?>>3</option>
                    <option value="04" <?php if(isset($toEmpDay) && $toEmpDay==04){ echo "selected"; }else if($toDayOrg == 04){echo "selected";} ?>>4</option>
                    <option value="05" <?php if(isset($toEmpDay) && $toEmpDay==05){ echo "selected"; }else if($toDayOrg == 05){echo "selected";} ?>>5</option>
                    <option value="06" <?php if(isset($toEmpDay) && $toEmpDay==06){ echo "selected"; }else if($toDayOrg == 06){echo "selected";} ?>>6</option>
                    <option value="07" <?php if(isset($toEmpDay) && $toEmpDay==07){ echo "selected"; }else if($toDayOrg == 07){echo "selected";} ?>>7</option>
                    <option value="08" <?php if(isset($toEmpDay) && $toEmpDay==8){ echo "selected"; }else if($toDayOrg == 8){echo "selected";} ?> >8</option>
                    <option value="09" <?php if(isset($toEmpDay) && $toEmpDay==9){ echo "selected"; }else if($toDayOrg == 9){echo "selected";} ?> >9</option>
                    <option value="10" <?php if(isset($toEmpDay) && $toEmpDay==10){ echo "selected"; }else if($toDayOrg == 10){echo "selected";} ?>>10</option>
                    <option value="11" <?php if(isset($toEmpDay) && $toEmpDay==11){ echo "selected"; }else if($toDayOrg == 11){echo "selected";} ?>>11</option>
                    <option value="12" <?php if(isset($toEmpDay) && $toEmpDay==12){ echo "selected"; }else if($toDayOrg == 12){echo "selected";} ?>>12</option>
                    <option value="13" <?php if(isset($toEmpDay) && $toEmpDay==13){ echo "selected"; }else if($toDayOrg == 13){echo "selected";} ?>>13</option>
                    <option value="14" <?php if(isset($toEmpDay) && $toEmpDay==14){ echo "selected"; }else if($toDayOrg == 14){echo "selected";} ?>>14</option>
                    <option value="15" <?php if(isset($toEmpDay) && $toEmpDay==15){ echo "selected"; }else if($toDayOrg == 15){echo "selected";} ?>>15</option>
                    <option value="16" <?php if(isset($toEmpDay) && $toEmpDay==16){ echo "selected"; }else if($toDayOrg == 16){echo "selected";} ?>>16</option>
                    <option value="17" <?php if(isset($toEmpDay) && $toEmpDay==17){ echo "selected"; }else if($toDayOrg == 17){echo "selected";} ?>>17</option>
                    <option value="18" <?php if(isset($toEmpDay) && $toEmpDay==18){ echo "selected"; }else if($toDayOrg == 18){echo "selected";} ?>>18</option>
                    <option value="19" <?php if(isset($toEmpDay) && $toEmpDay==19){ echo "selected"; }else if($toDayOrg == 19){echo "selected";} ?>>19</option>
                    <option value="20" <?php if(isset($toEmpDay) && $toEmpDay==20){ echo "selected"; }else if($toDayOrg == 20){echo "selected";} ?>>20</option>
                    <option value="21" <?php if(isset($toEmpDay) && $toEmpDay==21){ echo "selected"; }else if($toDayOrg == 21){echo "selected";} ?>>21</option>
                    <option value="22" <?php if(isset($toEmpDay) && $toEmpDay==22){ echo "selected"; }else if($toDayOrg == 22){echo "selected";} ?>>22</option>
                    <option value="23" <?php if(isset($toEmpDay) && $toEmpDay==23){ echo "selected"; }else if($toDayOrg == 23){echo "selected";} ?>>23</option>
                    <option value="24" <?php if(isset($toEmpDay) && $toEmpDay==24){ echo "selected"; }else if($toDayOrg == 24){echo "selected";} ?>>24</option>
                    <option value="25" <?php if(isset($toEmpDay) && $toEmpDay==25){ echo "selected"; }else if($toDayOrg == 25){echo "selected";} ?>>25</option>
                    <option value="26" <?php if(isset($toEmpDay) && $toEmpDay==26){ echo "selected"; }else if($toDayOrg == 26){echo "selected";} ?>>26</option>
                    <option value="27" <?php if(isset($toEmpDay) && $toEmpDay==27){ echo "selected"; }else if($toDayOrg == 27){echo "selected";} ?>>27</option>
                    <option value="28" <?php if(isset($toEmpDay) && $toEmpDay==28){ echo "selected"; }else if($toDayOrg == 28){echo "selected";} ?>>28</option>
                    <option value="29" <?php if(isset($toEmpDay) && $toEmpDay==29){ echo "selected"; }else if($toDayOrg == 29){echo "selected";} ?>>29</option>
                    <option value="30" <?php if(isset($toEmpDay) && $toEmpDay==30){ echo "selected"; }else if($toDayOrg == 30){echo "selected";} ?>>30</option>
                    <option value="31" <?php if(isset($toEmpDay) && $toEmpDay==31){ echo "selected"; }else if($toDayOrg == 31){echo "selected";} ?>>31</option>
                </select>
                <select  name="toEmpMonth" >
                    <option value="0" <?php if($toMonthOrg == 0){echo "selected";}?> >--</option>
                    <option value="01" <?php if(isset($toEmpMonth) && $toEmpMonth==01){ echo "selected"; }else if($toMonthOrg == 01){echo "selected";}  ?>>January</option>
                    <option value="02" <?php if(isset($toEmpMonth) && $toEmpMonth==02){ echo "selected"; }else if($toMonthOrg == 02){echo "selected";}  ?>>February</option>
                    <option value="03" <?php if(isset($toEmpMonth) && $toEmpMonth==03){ echo "selected"; }else if($toMonthOrg == 03){echo "selected";}  ?>>March</option>
                    <option value="04" <?php if(isset($toEmpMonth) && $toEmpMonth==04){ echo "selected"; }else if($toMonthOrg == 04){echo "selected";}  ?>>April</option>
                    <option value="05" <?php if(isset($toEmpMonth) && $toEmpMonth==05){ echo "selected"; }else if($toMonthOrg == 05){echo "selected";}  ?>>May</option>
                    <option value="06" <?php if(isset($toEmpMonth) && $toEmpMonth==06){ echo "selected"; }else if($toMonthOrg == 06){echo "selected";}  ?>>June</option>
                    <option value="07" <?php if(isset($toEmpMonth) && $toEmpMonth==07){ echo "selected"; }else if($toMonthOrg == 07){echo "selected";}  ?>>July</option>
                    <option value="08" <?php if(isset($toEmpMonth) && $toEmpMonth==8){ echo "selected"; }else if($toMonthOrg == 8){echo "selected";}  ?>>August</option>
                    <option value="09" <?php if(isset($toEmpMonth) && $toEmpMonth==9){ echo "selected"; }else if($toMonthOrg == 9){echo "selected";} ?>>September</option>
                    <option value="10" <?php if(isset($toEmpMonth) && $toEmpMonth==10){ echo "selected"; }else if($toMonthOrg == 10){echo "selected";}  ?>>October</option>
                    <option value="11" <?php if(isset($toEmpMonth) && $toEmpMonth==11){ echo "selected"; }else if($toMonthOrg == 11){echo "selected";}  ?>>November</option>
                    <option value="12" <?php if(isset($toEmpMonth) && $toEmpMonth==12){ echo "selected"; }else if($toMonthOrg == 12){echo "selected";}  ?>>December</option>
                </select>
                <input type="number" name="toEmpYear" placeholder="Year" min="1950" max="2018" value="<?php if(isset($toEmpYear) ){ echo $toEmpYear; }else if(isset($toYearOrg)){echo $toYearOrg;}  ?>">
            </div>
            <textarea name="empDesc" placeholder="Description of Employment"><?php 
                if(isset($empDesc)){echo $empDesc;}else{
                    echo $employment->description;
                }?></textarea>
            <input type="submit" name="empSubmit" value="Submit">
        </form>
    
    <?php } ?>
            
    
	
</div>

