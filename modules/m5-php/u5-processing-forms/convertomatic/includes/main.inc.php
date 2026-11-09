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
                        <div class="col-sm-5">
                            <input name="convert_value" id="convert_value" type="number" step="any"
                                class="form-control form-control-lg" placeholder="Your value here" required>
                        </div>
                        <div class="col-sm-5">
                            <select name="convert_type" id="convert_type" class="form-select form-select-lg mb-3">
                                <option selected value="c_to_f">Celsius to Fahrenheit</option>
                                <option value="f_to_c">Fahrenheit to Celsius</option>
                                <option value="km_to_mi">Kilometers to Miles</option>
                                <option value="mi_to_km">Miles to Kilometers</option>
                                <option value="cm_to_in">Centimeters to Inches</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-primary btn-lg mb-3">Convert</button>
                        </div>
                    </div>
                    <div class="row mb-3 gy-2">
                        <div class="col-sm-5">
                            <label for="convert_accuracy" class="form-label">Accuracy: <span
                                    id="accuracy_label">3</span> decimal places</label>
                            <input type="range" name="convert_accuracy" id="convert_accuracy" class="form-range" min="0"
                                max="5" step="1" oninput="updateAccuracy()">
                        </div>
                        <div class="col">
                            <label class="form-label" for="convert_keep_history">Keep History</label>
                            <div class="form-check form-switch">
                                <input name="convert_keep_history" id="convert_keep_history" class="form-check-input"
                                    type="checkbox" role="switch" style="transform: scale(1.5);">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="p-2 g-col-12">
        <div class="card">
            <div class="card-body">
                <?php
                // TODO Show results here
                ?>
            </div>
        </div>
    </div>
</div>
<script>
    function updateAccuracy() {
        const convertAccuracy = document.getElementById('convert_accuracy');
        document.getElementById('accuracy_label').innerHTML = convertAccuracy.value;
    }
</script>