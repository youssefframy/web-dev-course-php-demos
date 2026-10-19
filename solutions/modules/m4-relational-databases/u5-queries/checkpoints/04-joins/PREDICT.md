# Checkpoint 04: predictions

Observed on mariadb:11.4.13 with iceadmin at after-m4u4.

## joinlab

1. INNER JOIN: 4 rows. Roger (no department) and Marketing (no employees) are missing.
2. LEFT JOIN: 5 rows. Roger's `dept` is NULL.
3. RIGHT JOIN: 5 rows. Marketing has NULL name and surname.
4. Full outer join: 6 rows. Alice, Lucy, Grace and Joe match, so they appear in both halves, and UNION removes the duplicates. UNION ALL would give 10.
5. Error 1054: Unknown column 'Employee.name'. FULL is taken as an alias for Employee, so `Employee` no longer names anything in the query.

- [x] I predicted and ran all five.

## Where did Lucy go?

6. Lucy has gone from every result:
   - `bridge_inner.sql`: 3 rows (Joe Academic, Fred Academic, Fred Admissions). Lucy's link was deleted by `c_employeedepartment_employee ON DELETE CASCADE`.
   - `bridge_left.sql`: Admissions shows only Fred. Marketing shows NULL.
   - `lucy.sql`: 3 bookings, all Joe's and Fred's. Hers were deleted by `c_leavebooking_employee ON DELETE CASCADE`.
   - Vendor: Digital Marketing Institute has NULL, from `c_vendor_employee ON DELETE SET NULL`.

- [x] I predicted and ran all four, and I can say which Unit 4 rule caused each result.

Marking: accept any prediction. Each "What happened" must match the above, and question 6 must name the rule behind each result.
