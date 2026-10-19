# Checkpoint 04: predictions

For each question, write your prediction first. Then run the query and write what happened.

## joinlab

1. How many rows does the INNER JOIN return, and who is missing?

   Prediction:

   What happened:

2. How many rows does the LEFT JOIN return? What is in Roger's `dept`?

   Prediction:

   What happened:

3. How many rows does the RIGHT JOIN return? Which row has `NULL` for the name?

   Prediction:

   What happened:

4. How many rows does your full outer join return? Why does `UNION` not repeat Alice?

   Prediction:

   What happened:

5. What does `SELECT Employee.name, Employee.surname, Department.name AS dept FROM Employee FULL JOIN Department ON Department.id = Employee.deptId;` do? Run it and explain the result.

   Prediction:

   What happened:

- [ ] I predicted and ran all five.

## Where did Lucy go?

6. Lucy worked in Admissions, had two salary records and two leave bookings, and managed one vendor. After Unit 4, what will each of these show for her?
   - `bridge_inner.sql`
   - `bridge_left.sql`, for the Admissions row
   - `lucy.sql`
   - `SELECT name, brandManager FROM Vendor;`

   Prediction:

   What happened:

- [ ] I predicted and ran all four, and I can say which Unit 4 rule caused each result.
