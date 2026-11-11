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
    <?php if (count($errors) > 0) { ?>
        <div class="p-2 g-col-12">
            <div class="alert alert-danger mb-0">
                <?php
                foreach ($errors as $error) {
                    echo '<p class="mb-0">' . htmlspecialchars($error) . '</p>';
                }
                ?>
            </div>
        </div>
    <?php } ?>
    <?php if (count($studentList) > 0) { ?>
        <?php
        $q = $search !== '' ? '&q=' . urlencode($search) : '';
        $headers = ['id' => 'ID', 'name' => 'Name', 'surname' => 'Surname', 'grade' => 'Grade'];
        ?>
        <div class="p-2 g-col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="index.php" class="row mb-3 gy-2">
                        <div class="col-sm-10">
                            <input name="q" type="search" class="form-control" placeholder="Search by name, surname or grade"
                                value="<?= htmlspecialchars($search) ?>">
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-secondary w-100">Search</button>
                        </div>
                    </form>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <?php
                                foreach ($headers as $column => $title) {
                                    $icon = '';
                                    if ($column === $sortBy) {
                                        $icon = $sortDir === 'asc' ? ' <i class="bi bi-caret-up-fill"></i>' : ' <i class="bi bi-caret-down-fill"></i>';
                                    }
                                    printf('<th><a href="index.php?action=sort&sortBy=%s%s">%s%s</a></th>', $column, $q, $title, $icon);
                                }
                                ?>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($visibleStudents as $key => $student) {
                                printf(
                                    '<tr><td>%3d</td><td>%s</td><td>%s</td><td>%d</td><td>%s</td></tr>',
                                    $key,
                                    htmlspecialchars($student['name']),
                                    htmlspecialchars($student['surname']),
                                    $student['grade'],
                                    "<a href='index.php?action=delete&studId=$key$q'><i class='bi bi-trash3-fill'></i></a>"
                                );
                            }
                            if (count($visibleStudents) === 0) {
                                echo '<tr><td colspan="5">No students match your search.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                    printf(
                        '<p class="mb-0">Showing %d of %d students.</p>',
                        count($visibleStudents),
                        count($studentList)
                    );
                    ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
