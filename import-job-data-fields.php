<?php 
session_start();
error_reporting(0);
if($_SESSION['user']=='')
{
	header('Location: login.php');
}
else
{
	include 'php/config.php';
	date_default_timezone_set('Asia/Calcutta');
	$errorMessage = '';
	$successMessage = '';
	if (isset($_POST['submit'])) 
	{
	    $i=0; //so we skip first row
	    $file_ext = null;

	    $filename = $_FILES['fileToUpload']['name'];
	    if(empty($filename))
	    {
	    	$errorMessage = 'Please select file to upload';        //error if file not selected
	    }	
	    else
	    {
	    	$file_ext=strtolower(end(explode('.',$_FILES['fileToUpload']['name'])));
	    	
	    	if($file_ext!='csv')
	    	{
	    		$errorMessage = "Only csv files can be uploaded! Download sample file for your refrence!<br/>
	    			To download sample file <a href='sample_csv/sample_file_for_job_data_fields_import.csv' style='color:blue;'> Click here</a>";
	    	}
	    	else
	    	{
	    		$handle = fopen($_FILES['fileToUpload']['tmp_name'], "r");
				$conn = pg_connect($conn_string);
				if(!$conn)
				{
					$errorMessage = 'Could not connect to database';
				}

				$i=0;
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
				{
				    $i++;
				    if($i==1) continue;

				    $sql = "INSERT INTO visits(jobinfoid,visittype,visittypedesc,ismandatory) VALUES('$data[0]','$data[1]','$data[2]','$data[3]')";
					$result = pg_query($conn, $sql);

					if (!$result)
					{
						$errorMessage = 'Error in executing query';
					}
					else
					{
						$successMessage = 'File data imported successfully to database';
					}
					//break;
				}
			    fclose($handle);
			    pg_close($conn);
	    	}
	    	
	    }
	    
	}
	

?>
<html>
<head>
<title>Upload jobs data fields</title>

</head>
<body>
<?php

include 'header.php';
?>
<!-- Page Content  -->
        <div id="content" style="overflow: auto;">

            <nav class="navbar navbar-expand-lg navbar-light bg-light" style="width:100%">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="btn btn-info" style='background:#030dcf;'>
                        <i class="fas fa-align-left"></i>
                        
                    </button>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>

                    <div class="collapse navbar-collapse pull-right" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">
                        	<li class="nav-item">
                                <a href="jobinfo.php"style="color:blue;text-align:right;"  class="nav-link">Jobs List</a>
                            </li>
                            <li class="nav-item">
                                <a href="job-data-fields.php?jobinfoid=0"style="color:blue;text-align:right;"  class="nav-link">Job Data Fields</a>
                            </li>
                            <li class="nav-item">
                                <a href="jobdropdown.php?jobinfoid=0" style="color:blue;text-align:right;"  class="nav-link">Job Dropdown Values</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>  
		<div  class="col-md-12">
			<div  class="col-md-3">
			</div>
			<div  class="col-md-6">
		<h3>Import job data fields</h3>
		
			<form  method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
		    
			    For sample csv file <a href='sample_csv/sample_file_for_job_data_fields_import.csv' style='color:red;'>click here</a><br>
			    <p style="background-color:yellow;color:black;"><strong>Note: </strong>Please use  following details for csv file columns/your refrence<br/>
			    	<b style="color:blue;">jobinfoid = Job id </b> for <b style="color:red;">which you are importing data fields</b> <br/>
			    	<b style="color:blue;">visittype = 1 </b> for <b style="color:red;">"Description"</b><b style="color:blue;"> [Maximum 6 data-fields for description/text box] </b><br/>
			    	<b style="color:blue;">visittype = 2 </b> for <b style="color:red;">"Scanner"</b><b style="color:blue;"> [Maximum 4 data-fields for scanner] </b><br/>
			    	<b style="color:blue;">visittype = 3 </b> for <b style="color:red;">"Image"</b><b style="color:blue;"> [Maximum 2 data-fields for Image] </b><br/>
			    	<b style="color:blue;">visittype = 4 </b> for <b style="color:red;">"Date"</b><b style="color:blue;"> [Maximum 2 data-fields for Date] </b><br/>
			    	<b style="color:blue;">visittype = 5 </b> for <b style="color:red;">"Select/combo"</b><b style="color:blue;"> [Maximum 2 data-fields for Select/Combo box] </b><br/>
			    	<b style="color:blue;">visittypedesc = Description</b> for <b style="color:red;">current data field</b> <br/>
			    	<b style="color:blue;">ismandatory = 0 </b> for <b style="color:red;">"mandatory"</b><br/>
			    	<b style="color:blue;">ismandatory = 1 </b> for <b style="color:red;">"not mandatory"</b><br/>
			    </p> 
			    <br/><br/>
			    <table class='table table-bordered table-responsive table-condensed table-scroll table-fixed-header'  cellspacing='0' cellspacing='0' width='100%'>
			    	<tr>
			    		<td>
			    			<input type="file" name="fileToUpload" id="fileToUpload"  class='form-control form-control-sm'>
			    		</td>
			    		<td>
			    			<input type="submit" value="Upload File" name="submit" class='btn btn-sm btn-info'>
			    		</td>
			    	</tr>
			    	
			    </table>
			</form>
			<?php

				if ($errorMessage != '')
				{
					echo "<div class='alert alert-danger' style='padding: 10px; margin-bottom: 10px;'>";
					echo "<strong>Error!</strong> $errorMessage";
					echo "</div>";
				}

				if ($successMessage != '')
				{
					echo "<div class='alert alert-success' style='padding: 10px; margin-bottom: 10px;'>";
					echo "<strong>Success!</strong> $successMessage";
					echo "</div>";
				}

				?>
			</div>
			<div  class="col-md-3">
			</div>
		</div>
	</div>
		


<?php include 'footer.php'; }?>

</body>
</html>
