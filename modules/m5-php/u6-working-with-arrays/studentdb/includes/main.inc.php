<div class="grid gap-0 row-gap-3">
    <div class="p-2 g-col-12">
        <div class="card">
            <div class="card-body">
                <h1><i class="bi bi-mortarboard-fill"></i> StudentDB</h1>
            </div>
        </div>
    </div>
    <div class="p-2 g-col-12">
        <div class="card">
            <div class="card-body">
                <form name="studentForm" id="studentForm" method="POST" action="index.php">
                    <input type="hidden" name="action" value="add">
                    <div class="row mb-3 gy-2">
                        <div class="col-sm-4">
                            <input name="student_name" type="text" class="form-control form-control-lg"
                                placeholder="Name" required>
                        </div>
                        <div class="col-sm-4">
                            <input name="student_surname" type="text" class="form-control form-control-lg"
                                placeholder="Surname" required>
                        </div>
                        <div class="col-sm-2">
                            <input name="student_grade" type="number" min="0" max="100"
                                class="form-control form-control-lg" placeholder="Grade" required>
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-primary btn-lg">Add</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
