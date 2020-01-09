<!doctype html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>dailyTracks</title>
  <link href="css/style.css" rel="stylesheet" type="text/css">
</head>


<body>

  <div>
      <div id="content">
        <div>
            <h1> TODAY'S CONTENT </h1>
            <br/><br/><br/>
            <h3> Date : <?php echo date("d/m/Y")?></h3>
        </div>

        <div class = videoList>
            <?php
            //Get the suggested videos in the file
            $jsonText = file_get_contents("suggestion.json");
            $propositionJson = json_decode($jsonText, $assoc = TRUE);

            //get the currently selected videos
            $jsonText = file_get_contents("selection.json");
            $selectionJson = json_decode($jsonText, $assoc = TRUE);
            

            //Displaying the list of videos
            foreach ($propositionJson as $video){
                $urlVid = 'https://www.youtube.com/watch?v='.$video['id'];
            
                echo '<div class = "videoBlock">';
                    echo '<a href = '.$urlVid.' target="_blank" and rel="noopener noreferrer">';
                        echo '<p>';
                        echo ($video['title']);
                        echo '<br/><br/>';
                        echo 'Score : '.$video['score'];
                        echo '</p>';
                    echo '</a>';
                          
                    echo '<div class = "embeddedBlock">';
                        $urlImg ='https://img.youtube.com/vi/'.$video['id'].'/3.jpg'; 
                        echo '<button type="button" class="block" id = "button_'.$video['id'].'">Select today</button>';
                    echo '</div>';
                echo '</div>';
            }
            ?>    
        </div>
</div>
</div>  
</div>
</body>
</html>



<script src= "js/jquery-3.4.1.js"> </script>
<script>
    //sound
    var audio = new Audio('audio/bell.wav');
    
    //videos id list, and current selection
    var data = <?php echo json_encode($propositionJson, JSON_HEX_TAG); ?>; 
    var $selectionCurrent = <?php echo json_encode($selectionJson, JSON_HEX_TAG); ?>; 
    console.log($selectionCurrent);

    $selectBools = {}
    $selectionCurrent.forEach($id => {
        $selectBools[$id] =true;
    })

    //DOM handling
    data.forEach(video =>  
    {
    document.getElementById("button_"+video['id']).addEventListener('click', function(){ clickButtonVid(video['id']) }); 
        
        if ( !$selectBools[video['id']] ){
            $selectBools[video['id']]= false; 
        }
        updateButton(video['id']);
    
     });

     //update button appearance
     function updateButton($id){
        if ($selectBools[$id]){
            $color = "#4CAF50";
            $buttonLabel = "Selected";
        }
        else{
            $color = "rgb(179, 21, 0)";
            $buttonLabel = "Select";
        }
        document.getElementById("button_"+$id).style.backgroundColor = $color;
        document.getElementById("button_"+$id).innerHTML = $buttonLabel;

     }


    //when a button is clicked
    function clickButtonVid($id){
        $newState  = !$selectBools[$id];
        if ($newState){
            audio.play();
        }
        $selectBools[$id] = $newState;
        updateButton($id);
        
        //updates selection
        $selection = [];
        data.forEach(video =>  
            {
                if ( $selectBools[video['id']]){
                    $selection.push( video['id']);
                } 
            });
                    
        //Asking server to save selection
        console.log($selection);
        $.ajax({
            url : 'selectionSaver.php',
            type : 'POST',
            data : {'ids' : $selection},
            
            error : function(resultat, statut, erreur){
                alert("Error while saving selection")
            }
        });


    }
</script>



