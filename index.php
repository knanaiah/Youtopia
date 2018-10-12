        <?php
        //Uses header and footer templates to create layout.
        
        session_start();
        include('templates/header.html');
        include('/dataaccess/DBConnect.php');
        
        //Connect to database
        $dbObj = new DBConnect();
        $dbObj->selectDB();
        
        //ALL REQUESTS FOR DATA SHOULD BE REST BASED
        
        //This page filters communities based on state, county, city
        echo "<h2>Find Your Community</h2>";
        //store results in session!!!
        //START HERE!!!
        //All queries should execute only once - after that retrieve data from session unless it needs to be refreshed
        //$result = mysql_query("select stateCode, stateName from state");
        echo "<form action='index.php' method='post' autocomplete='off'>";
        echo "<table>";        
        echo "<tr><td width=15%><select name='state' onchange='this.form.submit()'>";
            
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<option selected>$_POST[state]</option>";
            if (isset($_SESSION['currState'])) {
                $_SESSION['prevState'] = $_SESSION['currState'];
            } else {
                $_SESSION['prevState'] = "";
            }
            $_SESSION['currState'] = $_POST['state'];
            $states = $_SESSION['states'];
        } else {
            $result = mysql_query("select * from state");
            $states = array();
            while ($row = mysql_fetch_array($result)) {  
                $states[] = $row['stateName'];
            }
            $_SESSION['states'] = $states;            
            echo "<option disabled selected>Select Your State</option>";
        }  
        /*while ($row = mysql_fetch_array($result)) {
            echo "<option value='" . $row['stateName'] ."'>" . $row['stateName'] ."</option>";
        }*/
        
        foreach($states as $item) {
            //echo "<option value='" . $item[stateName] ."'>" . $item[stateName] ."</option>";            
            echo "<option value='" . $item ."'>" . $item ."</option>";            
        }
        
        echo "</select></td>";
        echo "<td width=15%><select name='county' onchange='this.form.submit()'>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            /*$result = mysql_query("select distinct county from cities_extended where state_code='$_POST[state]' "
                    . "and county != '' order by 1");*/
            if (!empty($_POST['state'])) {            
                $result = mysql_query("select distinct county from cities_extended c, state s where "
                        . "c.state_code = s.stateCode and s.stateName='$_POST[state]' "
                        . "and county != '' order by 1"); 
                if (mysql_num_rows($result) == 0) { 
                    $counties = 'NONE'; 
                }
                if (!empty($_POST['county']) && ($_SESSION['currState'] == $_SESSION['prevState'])) {
                    echo "<option selected>$_POST[county]</option>";
                    if (isset($_SESSION['currCounty'])) {
                        $_SESSION['prevCounty'] = $_SESSION['currCounty'];
                    } else {
                        $_SESSION['prevCounty'] = "";
                    }
                    $_SESSION['currCounty'] = $_POST['county'];
                } else {
                    echo "<option selected>Select Your County</option>"; 
                }
                while ($row = mysql_fetch_array($result)) {
                    echo "<option value='" . $row['county'] ."'>" . $row['county'] ."</option>";
                }
            } else {
                echo "<option disabled selected>Select Your City</option></td>";                
            }
        } else {
            echo "<option disabled selected>Select Your County</option></td>";            
        }  
        echo "<td width=15%><select name='city' onchange='this.form.submit()'>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!empty($_POST['county'])) {
                /*$result = mysql_query("select distinct city from cities_extended where county='$_POST[county]' "
                        . "and state_code='$_POST[state]' order by 1");*/
                $result = mysql_query("select distinct city from cities_extended c, state s where "
                    . "c.state_code = s.stateCode and s.stateName='$_POST[state]' "
                    . "and c.county = '$_POST[county]' order by 1");
                if (mysql_num_rows($result) == 0) { 
                    $cities = 'NONE'; 
                }
                if (!empty($_POST['city']) && ($_SESSION['currState'] == $_SESSION['prevState'])
                        && ($_SESSION['currCounty'] == $_SESSION['prevCounty'])) {
                    echo "<option selected>$_POST[city]</option>";
                    if (isset($_SESSION['currCity'])) {
                        $_SESSION['prevCity'] = $_SESSION['currCity'];
                    } else {
                        $_SESSION['prevCity'] = "";
                    }
                    $_SESSION['currCity'] = $_POST['city'];
                } else {
                    echo "<option selected>Select Your City</option>"; 
                }                
                while ($row = mysql_fetch_array($result)) {
                    echo "<option value='" . $row['city'] ."'>" . $row['city'] ."</option>";
                }
            } else {
                echo "<option disabled selected>Select Your City</option></td>";                
            }
        } else {
            echo "<option disabled selected>Select Your City</option></td>";                
        }
        echo "</tr></table>";
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($counties) || isset($cities)) {
                echo "<p><h3>NO DATA!!!</h3></p>";
            } else {
            if (!empty($_POST['city']) && $_POST['city'] != 'Select Your City') {
                $result = mysql_query("select cc.id as Id, cc.name as Name, "
                        . "cc.comments as Description, ct.name as Type from communities cc, "
                        . "community_type ct where city='$_POST[city]' and cc.Community_Type = ct.Code order by cc.name");
                echo "<p>";
                echo "<h3>Communities in $_POST[city]:</h3>"; 
                echo "<div style='overflow-y:auto;'>";
                echo "<table>";
                echo "<tr><th>Name</th><th>Description</th><th>Type</th></tr>";
                while ($row = mysql_fetch_array($result)) {  
                    echo "<tr><td><a href=community.php?CommunityId=".$row['Id'].""
                            . "&CommunityName=",urlencode($row['Name']),">".$row['Name']."</td>";  
                    echo "<td>".$row['Description']."</td>";
                    echo "<td>".$row['Type']."</td></tr>";                    
                } 
                echo "</table>"; 
                echo "</div>";
            }
        } }
        
        echo "</form>";

        $dbObj->closeConnection();
        
        include('templates/footer.html');