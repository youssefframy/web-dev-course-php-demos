<div class="grid gap-0 row-gap-3">
    <div class="p-2 g-col-12">
        <div class="card">
            <div class="card-body">
                <h1><span class="logo"><i class="bi bi-brilliance"></i></span></a> Convertomatic</h1>
            </div>
        </div>
    </div>
    <div class="p-2 g-col-12">
        <div class="card">
            <div class="card-body">
                <form name="convertForm" id="convertForm" method="POST">
                    <div class="row mb-3 gy-2">
                        <div class="col-sm-4">
                            <input name="convert_value" id="convert_value" type="number" step="any"
                                class="form-control form-control-lg" placeholder="Your value here" required
                                value="<?= htmlspecialchars($inputValue ?? '') ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="convert_from" id="convert_from" class="form-select form-select-lg mb-3"
                                aria-label="From">
                                <?php
                                foreach ($units as $code => $name) {
                                    $selected = $code === $from ? 'selected' : '';
                                    echo "<option value='$code' $selected>$name</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="convert_to" id="convert_to" class="form-select form-select-lg mb-3"
                                aria-label="To">
                                <?php
                                foreach ($units as $code => $name) {
                                    $selected = $code === $to ? 'selected' : '';
                                    echo "<option value='$code' $selected>$name</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-primary btn-lg mb-3">Convert</button>
                        </div>
                    </div>
                    <div class="row mb-3 gy-2">
                        <div class="col-sm-5">
                            <label for="convert_accuracy" class="form-label">Accuracy: <span
                                    id="accuracy_label"><?= $accuracy ?></span> decimal places</label>
                            <input type="range" name="convert_accuracy" id="convert_accuracy" class="form-range" min="0"
                                max="5" step="1" oninput="updateAccuracy()" value="<?= $accuracy ?>">
                        </div>
                        <div class="col">
                            <label class="form-label" for="convert_keep_history">Keep History</label>
                            <div class="form-check form-switch">
                                <input name="convert_keep_history" id="convert_keep_history" class="form-check-input"
                                    type="checkbox" role="switch" style="transform: scale(1.5);" <?= $keepHistory ? 'checked' : '' ?>>
                            </div>
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
                    echo "<p class='mb-0'>$error</p>";
                }
                ?>
            </div>
        </div>
    <?php } ?>
    <?php
    if (isset($result) || (isset($_SESSION['conversionHistory']) && count($_SESSION['conversionHistory']) > 0)) {
    ?>
        <div class="p-2 g-col-12">
            <div class="card">
                <div class="card-body">
                    <?php
                    if (isset($result)) {
                        printf("<h3>%.{$accuracy}f%s = %.{$accuracy}f%s</h3>", $result['fromValue'], $result['fromUnit'], $result['toValue'], $result['toUnit']);
                        echo '<hr>';
                    }
                    if (isset($_SESSION['conversionHistory'])) {
                        $history = $_SESSION['conversionHistory'];
                        // The newest conversion is already shown above when there is a result
                        $newest = isset($result) ? count($history) - 2 : count($history) - 1;
                        for ($i = $newest; $i >= 0; $i--) {
                            $item = $history[$i];
                            printf(
                                "<h4 class='text-body-secondary'>%.{$accuracy}f%s = %.{$accuracy}f%s</h4>",
                                $item['fromValue'],
                                $item['fromUnit'],
                                $item['toValue'],
                                $item['toUnit']
                            );
                            echo $i === 0 ? '' : '<hr>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<script>
    // The same list the server checks against
    const conversions = <?= json_encode($conversions) ?>;
    const convertFrom = document.getElementById('convert_from');
    const convertTo = document.getElementById('convert_to');

    // Show only the To units that the chosen From unit can convert to
    function filterToOptions() {
        let firstAllowed = null;
        for (const option of convertTo.options) {
            const allowed = conversions.includes(`${convertFrom.value}_to_${option.value}`);
            option.hidden = !allowed;
            option.disabled = !allowed;
            if (allowed && firstAllowed === null) {
                firstAllowed = option;
            }
        }
        if (convertTo.selectedOptions[0].disabled) {
            firstAllowed.selected = true;
        }
    }

    convertFrom.addEventListener('change', filterToOptions);
    filterToOptions();

    function updateAccuracy() {
        const convertAccuracy = document.getElementById('convert_accuracy');
        document.getElementById('accuracy_label').innerHTML = convertAccuracy.value;
    }
</script>