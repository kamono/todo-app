<?php
session_start();
require_once('includes/functions.php');
include 'includes/header.html';
?>
<section>
    <div class="row">
        <article class="col-sm-7">
            <div class="scrolling">
                <table class="table table-striped panel-group" id="accordion">
                    <tr>
                        <th>done</th>
                        <th>task</th>
                        <th>date due</th>
                    </tr>
                    <?php
                        // display users tasks
                        echo getTasks();
                    ?>
                </table>
            </div>
        </article>

        <aside class="col-sm-5">
            <div class="well">
                <header>
                    <?php 
                        if(isset($_SESSION['error'])) {
                            echo '<h4><span class="label label-danger">Please do not leave any field blank</span></h4>
                    <h2>Create Task</h2>';
                            unset($_SESSION['error']);
                        }
                    ?>						
                </header>

                <form role="form" action="process.php" method="POST" >
                    <div class="form-group">
                        <label for="taskName">Task Name</label>
                        <input type="text" class="form-control" name="taskName">
                    </div>
                    <div class="form-group">
                        <label for="taskDescription">Description</label>
                        <textarea row="3" class="form-control" name="taskDescription"></textarea>								
                    </div>
                    <div class="form-group">
                        <label for="taskDueDate">Due Date</label>
                        <input type="date" class="form-control" name="taskDueDate">
                    </div>
                    <div class="form-group">
                        <label>Urgency Level</label>
                        <div class="container">
                            <label class="radio-inline">
                                <input type="radio" name="urgency" id="inlineRadio3" value="0" checked>
                                <span class="label label-info">Low</span>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="urgency" id="inlineRadio1" value="1">
                                <span class="label label-warning">Medium</span>
                                
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="urgency" id="inlineRadio1" value="2">
                                <span class="label label-danger">High</span>
                            </label>
                        </div>									
                    </div>
                    <div>
                        <button type="submit" name="submit" class="btn btn-default">Submit</button>                        
                    </div>
                </form>
            </div>
        </aside>
    </div>
</section>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this task?
      </div>
      <div class="modal-footer">
        <button type="button" id="cancel" class="btn btn-secondary" data-dismiss="modal" value="false">Cancel</button>
        <button type="button" id="delete" class="btn btn-primary" value="true">Delete</button>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.html'; ?>

<script src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
    // ajax request used to delete selected task
    $(document).ready(function(){
        $(':checkbox').change(function(e) {            

            if ($(this).is(":checked")){
                var response = confirm("Are you sure?");                
                //$('#myModal').modal('show');

                if(response) {
                    // get the id of the task clicked
                    var taskid = e.target.id; 
                    // reference to the checkbox element used 
                    // in ajax success
                    var $t = $(this);
                    
                    var formData = {task:taskid,checked:"true"};
                    $.ajax({
                        url : "process.php",
                        type: "POST",
                        dataType: "json",
                        data : formData,
                        success: function(data) {
                            // removes selected row and sibling row, which
                            // is the hidden collapse row
                            $t.parent().parent().next().remove();
                            $t.parent().parent().remove();
                        },
                        error: function (data) {
                            alert(data.text);
                        }
                    });
                } else {
                    $(':checkbox').attr('checked', false); // Unchecks it
                }
            }

        }); 
    });

</script>
</body>
</html>