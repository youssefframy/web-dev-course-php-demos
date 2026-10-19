# Requirements worksheet: ICEAdmin

## 1. Scenario

> ICE Malta wants a system to store employee details. Every employee works in at least one department, and some employees work in more than one. Each employee's salary must be stored, and past salaries must be kept as well as the current one.

## 2. Nouns: candidate entities

| Noun | Entity, attribute or neither | Reason |
|---|---|---|
| ICE Malta | Neither | The organisation the system is for. There is only one. |
| System | Neither | The thing being built. |
| Employee | Entity | Many employees, each with their own details. |
| Employee details | Attributes | The scenario's general term for an employee's attributes. |
| Department | Entity | Several departments, linked to employees. |
| Salary | Entity | Each employee has several salaries over time (current and past). |
| Past salaries | Same entity as Salary | A past salary is a salary with an end date. |

## 3. Entities and their attributes

| Entity | Attributes | Stated in the scenario or assumed? |
|---|---|---|
| Employee | name, surname, jobTitle | Assumed ("employee details") |
| Department | name | Assumed |
| Salary | amount, fromDate, toDate | amount stated. Dates assumed, because they are needed to tell past salaries from the current one. |

## 4. Verbs: relationships

| Entity A | Verb phrase | Entity B | Quantifier wording | Multiplicity (A to B) |
|---|---|---|---|---|
| Employee | works in | Department | "at least one", "more than one" | An employee has one or many departments. A department has zero or many employees. |
| Employee | is paid | Salary | "each employee's salary", "past salaries" | An employee has one or many salaries. A salary belongs to one and only one employee. |

## 5. Assumptions and open questions

- An employee has exactly one current salary. It is the one with no `toDate`.
- A department may exist before anyone is assigned to it, so the department side's minimum is zero.
- The attributes in section 3 are assumed. The scenario lists none.
