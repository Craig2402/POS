<?php
 class categoriesController{

 	/*=============================================
	CREATE CATEGORY
	=============================================*/
	
	static public function ctrCreateCategory(){

		if(isset($_POST['newCategory'])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["newCategory"])){

				$table = 'categories';

				$data = $_POST['newCategory'];

				$answer = CategoriesModel::mdlAddCategory($table, $data);
				// var_dump($answer);

				if($answer == 'ok'){
	
					// Create an array with the data for the activity log entry
					$logdata = array(
						'UserID' => $_SESSION['userId'],
						'ActivityType' => 'Category',
						'ActivityDescription' => 'User ' . $_SESSION['username'] . ' created category ' . $data . '.'
					);
	
					// Call the ctrCreateActivityLog() function
					activitylogController::ctrCreateActivityLog($logdata);

					echo '<script>
						
                    Swal.fire({
							icon: "success",
							title: "Category has been successfully saved ",
							showConfirmButton: true,
							confirmButtonText: "Close"

							}).then(function(result){
								if (result.value) {

									window.location = "category";

								}
							});
						
					</script>';
				}
				

			}else{

				echo '<script>
						
                Swal.fire({
							icon: "error",
							title: "No especial characters or blank fields",
							showConfirmButton: true,
							confirmButtonText: "Close"
				
							 }).then(function(result){

								if (result.value) {
									window.location = "category";
								}
							});
						
				</script>';
				
			}
		}
	}

	/*=============================================
	SHOW CATEGORIES
	=============================================*/

	static public function ctrShowCategories($item, $value){

		$table = "categories";

		$answer = CategoriesModel::mdlShowCategories($table, $item, $value);

		return $answer;
	}

	/*=============================================
	EDIT CATEGORY
	=============================================*/

	static public function ctrEditCategory(){

		if(isset($_POST["editCategory"])){

			$table = "categories";

			$data = array("Category"=>$_POST["editCategory"],
							"id"=>$_POST["idCategory"]);
			
			$item = "id";
			$value = $data['id'];
			$oldItem = CategoriesModel::mdlShowCategories($table, $item, $value);

            $changedInfo = ''; // Initialize the changed information string

            foreach ($data as $property => $value) {
                if ($property !== 'id' && $oldItem[$property] !== $value) {
                    $changedInfo .= "Property $property changed from {$oldItem[$property]} to $value. ";
                }
            }
            
            // If any properties were changed, use the changed information as the log message
            if (!empty($changedInfo)) {
                $logMessage = $changedInfo;
            } else {
                $logMessage = "Category has been edited.";
            }

			$answer = CategoriesModel::mdlEditCategory($table, $data);

			if($answer == "ok"){
				// Create an array with the data for the activity log entry
				$logdata = array(
					'UserID' => $_SESSION['userId'],
					'ActivityType' => 'Category',
					'ActivityDescription' => $logMessage,
                	'itemID' => $value
				);
				// Call the ctrCreateActivityLog() function
				activitylogController::ctrCreateActivityLog($logdata);

				echo'<script>

				Swal.fire({
						icon: "success",
						title: "Category has been successfully saved ",
						showConfirmButton: true,
						confirmButtonText: "Close"
						}).then(function(result){
								if (result.value) {

								window.location = "category";

								}
							})

				</script>';

			}

		}

	}

	/*=============================================
	DELETE CATEGORY
	=============================================*/

	static public function ctrDeleteCategory(){

		if(isset($_GET["idCategory"])){

			$table ="categories";
			$data = $_GET["idCategory"];

			$item = "id";
			$value = $data;
			$loganswer = CategoriesModel::mdlShowCategories($table, $item, $value);

			$category = $loganswer['Category'];

			$answer = CategoriesModel::mdlDeleteCategory($table, $data);

			if($answer == "ok"){
				
				// Create an array with the data for the activity log entry
				$logdata = array(
					'UserID' => $_SESSION['userId'],
					'ActivityType' => 'Category',
					'ActivityDescription' => 'User ' . $_SESSION['username'] . ' deleted category ' .$category. '.',
                    'itemID' => $value
				);
				// Call the ctrCreateActivityLog() function
				activitylogController::ctrCreateActivityLog($logdata);

				echo'<script>

                Swal.fire({
						  icon: "success",
						  title: "The category has been successfully deleted",
						  showConfirmButton: true,
						  confirmButtonText: "Close"
						  }).then(function(result){
									if (result.value) {

									window.location = "category";

									}
								})

					</script>';
			}
		
		}
		
	}

}