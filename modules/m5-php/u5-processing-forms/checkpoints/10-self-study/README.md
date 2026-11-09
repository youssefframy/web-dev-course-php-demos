# Checkpoint 10: Self-study

## Goal

Use what you have learnt in this unit, without step-by-step instructions, to make Convertomatic check every field on the server and convert more units.

## Concepts

Nothing new for the main tasks. You need:

- `in_array()` and `filter_input()`, from checkpoints 05 and 07
- `FILTER_VALIDATE_INT` with a range. Its options go in the fourth argument: `['options' => ['min_range' => 0, 'max_range' => 5]]`
- the `convert()` function from checkpoint 09

For the extra credit, you need two pieces of JavaScript:

- a `change` event on a `<select>`, which fires when the visitor picks another option
- an `<option>`'s `hidden` and `disabled` properties, which hide it and stop it being chosen

## Before you start

You need Convertomatic from checkpoint 09. If you skipped it, copy the reference answer over yours:

```sh
cp -R solutions/modules/m5-php/u5-processing-forms/checkpoints/09-convertomatic/convertomatic/. modules/m5-php/u5-processing-forms/convertomatic/
```

## Steps

1. The server trusts the conversion type and the accuracy, because the HTML only offers valid ones. Send a bad one with `curl` and see what happens:

   ```sh
   curl -s http://localhost:8000/index.php -d convert_value=1 -d convert_type=bogus -d convert_accuracy=9
   ```

2. Add server-side validation, with a message in the error box for each problem:
   - The conversion type must be one the app supports. Keep the supported types in one array, so a new conversion only needs adding in one place.
   - The accuracy must be a whole number from 0 to 5.
   - Only convert when there are no errors at all.
3. Add three conversions: inches to centimetres, euro to pounds sterling, and pounds sterling to euro. An approximate exchange rate is fine.
4. Check every conversion in both directions, and that the history still works.
5. Extra credit: replace the single drop-down with two, From and To. When the visitor picks a From unit, JavaScript hides every To unit it cannot be converted to, and selects the first one it can. The server still checks the pair, because JavaScript can be turned off.

## Done when

- [ ] The `curl` request from step 1 gets two error messages, and no PHP warnings.
- [ ] Inches to centimetres, euro to pounds and pounds to euro all work.
- [ ] Extra credit: the To list only offers units that the From unit can be converted to.
- [ ] Your work is committed and pushed (see [How to submit](../../../../../README.md#how-to-submit)).

## Previous / Next

Previous: [09 Convertomatic](../09-convertomatic/README.md). Next: Unit 6, coming soon.
