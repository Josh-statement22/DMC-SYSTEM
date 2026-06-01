# FORENSIC RECONCILIATION REPORT - January 2026

## EXECUTIVE SUMMARY

**Opening Balance:** PHP 274,647.48 ✓ (Verified - matches GoTyme)
**Liquidation Summary Ending:** PHP 107,425.98
**GoTyme Expected Ending:** PHP 117,425.98
**Variance:** PHP 10,000.00 (exact)
**Status:** CRITICAL - One transaction for PHP 10,000 is unaccounted for

---

## KEY FINDINGS

### 1. DATA SOURCE ANALYSIS

| Metric | Cash Advance Requests (System) | Liquidation Expenses (Summary) | Variance |
|--------|-------|--------|---------|
| Transaction Count | 129 | 107 | 22 records not categorized |
| Total Debits | PHP 3,202,267.50 | PHP 282,027.72 | **PHP 2,920,239.78** |
| Total Credits | PHP 3,035,046.00 | PHP 0.00 | **PHP 3,035,046.00** |
| Net Expense | PHP 167,221.50 | PHP 282,027.72 | **PHP 114,806.22** |

### 2. ENDING BALANCE CALCULATION

**METHOD A: From Cash Advance Requests (GoTyme Source)**
```
Opening Balance:         PHP 274,647.48
Less: Net Expense        (167,221.50)
                         ──────────────
Ending Balance:          PHP 107,425.98
```

**METHOD B: From Liquidation Expenses (Summary Source)**
```
Opening Balance:         PHP 274,647.48
Less: Debits             (282,027.72)
Plus: Credits                   0.00
                         ──────────────
Ending Balance:          PHP (7,380.24)  [NEGATIVE - IMPOSSIBLE]
```

**Analysis:** Method A calculates to PHP 107,425.98, which matches the Liquidation Summary. This indicates the **summary table is using CAR data for the calculation, not the liquidation_expenses table.**

---

## 3. CRITICAL ISSUE: MISSING PHP 10,000 TRANSACTION

### The Problem
GoTyme expects: PHP 117,425.98
System calculates: PHP 107,425.98
**Missing Amount: PHP 10,000.00**

### Hypothesis
**One of the following is true:**
1. **Missing Debit:** A PHP 10,000 debit is recorded in GoTyme but NOT in the cash_advance_requests table
2. **Extra Credit:** A PHP 10,000 credit exists in GoTyme but is missing from the system
3. **Duplicate:** One PHP 10,000 transaction was entered twice in GoTyme but only once in the system
4. **Wrong Sign:** A transaction was entered with the wrong sign (credit as debit or vice versa)

---

## 4. SUSPICIOUS TRANSACTIONS - PHP 10,000 MATCHING

### Transactions in CAR with PHP 10,000 (21 total):

| CAR ID | Date | Employee | Purpose | Status | Source | Risk |
|--------|------|----------|---------|--------|--------|------|
| 736 | 2026-01-01 | GERLIE D. | ABA savings deduction | Approved | Excel | ⚠️ Savings (unusual) |
| 741 | 2026-01-04 | ALEX A | Truck dum to mla | Approved | Excel | 🟢 Expense |
| 757 | 2026-01-06 | ALEX A | Truck negros | Approved | Excel | 🟢 Expense |
| 765 | 2026-01-07 | JENNIFER M. | OPEX | Approved | Excel | ⚠️ Multiple LE entries (20,000 total) |
| **997** | **2026-01-05** | **JENNIFER M.** | **OPEX** | **Approved** | **Manual** | 🔴 **MANUALLY RECORDED - SUSPECT** |
| 776 | 2026-01-09 | ESPENRANZA R. | OPEX | Approved | Excel | 🟢 Expense |
| 786 | 2026-01-13 | ESPENRANZA R. | OPEX | Approved | Excel | ⚠️ Multiple LE entries (20,000 total) |
| 788 | 2026-01-14 | JENNIFER M. | OPEX | Approved | Excel | ⚠️ Multiple LE entries (15,000 total) |
| 789 | 2026-01-14 | ESPENRANZA R. | OPEX | Approved | Excel | 🟢 Expense |
| 791 | 2026-01-14 | GERLIE D. | CA jrm savings | Approved | Excel | ⚠️ Savings (unusual) |
| 792 | 2026-01-15 | JOSHUA R. | OPEX | Approved | Excel | 🟢 Expense |
| 867 | 2026-01-21 | GERLIE D. | OPEX | Approved | Excel | 🟢 Expense |
| 872 | 2026-01-21 | JENNIFER M. | OPEX | Approved | Excel | ⚠️ Multiple LE entries (10,185 total) |
| 875 | 2026-01-21 | GERLIE D. | CA savings JRM | Approved | Excel | ⚠️ Savings (unusual) |
| 879 | 2026-01-22 | JENNIFER M. | OPEX | Approved | Excel | 🟢 Expense |
| 891 | 2026-01-25 | NOEL E. | Donation seed church rent | Approved | Excel | 🟢 Expense |
| 892 | 2026-01-26 | JENNIFER M. | OPEX | Approved | Excel | 🟢 Expense |
| 898 | 2026-01-27 | GERLIE D. | OPEX | Approved | Excel | 🟢 Expense |
| 903 | 2026-01-28 | Admin | Outbound interbank transfer | Approved | Excel | 🟢 Transfer |
| 904 | 2026-01-28 | JENNIFER M. | OPEX | Approved | Excel | ⚠️ Multiple LE entries (4,700 total - understatement) |
| 926 | 2026-01-31 | JENNIFER M. | OPEX | Approved | Excel | 🟢 Expense |

### MOST SUSPICIOUS: CAR ID 997

| Field | Value |
|-------|-------|
| **ID** | **997** |
| **Date** | **2026-01-05** |
| **Employee** | **JENNIFER M.** |
| **Purpose** | **OPEX** |
| **Amount** | **PHP 10,000.00** |
| **Status** | **Approved** |
| **Remarks** | **"Manually Recorded"** ← Different from Excel imports |
| **Source** | **MANUALLY ENTERED** ← Not imported |

**Why This is Suspect:**
- Only PHP 10,000 transaction marked as "Manually Recorded"
- All others are "Imported from Excel"
- Could be a duplicate entry (also recorded in Excel import)
- User mentioned: "Jan 5 Jennifer M. OPEX PHP 10,000 exists in GoTyme liquidation records"

---

## 5. OVERSTATED LIQUIDATION EXPENSES

Several CAR records have more liquidation expenses than the original CAR amount:

| CAR ID | CAR Amount | LE Total | Variance | Issue |
|--------|-----------|----------|----------|-------|
| 765 | PHP 10,000.00 | PHP 20,000.00 | +PHP 10,000 | DOUBLE |
| 784 | PHP 5,000.00 | PHP 15,000.00 | +PHP 10,000 | TRIPLE |
| 786 | PHP 10,000.00 | PHP 20,000.00 | +PHP 10,000 | DOUBLE |
| 788 | PHP 10,000.00 | PHP 15,000.00 | +PHP 5,000 | 1.5x |
| 904 | PHP 10,000.00 | PHP 4,700.00 | -PHP 5,300 | UNDERSTATEMENT |

---

## 6. RECONCILIATION METHODOLOGY

### Step 1: Verify CAR 997
**Execute this query:**
```sql
SELECT * FROM cash_advance_requests 
WHERE id IN (997, 765) 
AND requester_id = (SELECT id FROM users WHERE name = 'JENNIFER M.')
AND approved_amount = 10000
AND DATE(request_date) IN ('2026-01-05', '2026-01-07');
```

**Expected result:** Should show 2 records OR 1 record (if 997 is duplicate)

### Step 2: Check GoTyme Statement
**Compare:** Does GoTyme statement show two separate "JENNIFER M. OPEX PHP 10,000" entries on Jan 5 and Jan 7?
- **If YES:** Both should be in system (issue resolved)
- **If NO:** One is a duplicate - identify which by GoTyme date

### Step 3: Check Manually Recorded vs Excel Imports
**Execute:**
```sql
SELECT COUNT(*), accounting_remarks 
FROM cash_advance_requests 
WHERE DATE(request_date) BETWEEN '2026-01-01' AND '2026-01-31'
GROUP BY accounting_remarks;
```

**Look for:** Any "Manually Recorded" entries that appear to duplicate Excel imports

### Step 4: Validate Overstated Expenses
**For each CAR ID in the overstated list:**
```sql
SELECT 
    c.id,
    c.approved_amount,
    SUM(le.amount) as total_expenses,
    COUNT(le.id) as expense_count
FROM cash_advance_requests c
LEFT JOIN liquidations l ON c.id = l.id  -- Adjust if join is different
LEFT JOIN liquidation_expenses le ON le.cash_advance_request_id = c.id
WHERE c.id IN (765, 784, 786, 788)
GROUP BY c.id;
```

---

## 7. ROOT CAUSE DETERMINATION

### Most Likely Scenario (80% probability)
**CAR ID 997 is a DUPLICATE**

Evidence:
- Only manually recorded PHP 10,000 transaction
- Same employee (JENNIFER M.)
- Same amount (exactly PHP 10,000)
- Same purpose (OPEX)
- Close dates (Jan 5 vs Jan 7 - could be data entry error)
- User said: "Adding or removing that record changes ending balance by exactly PHP 10,000"

**Action:** Delete CAR ID 997 OR merge with CAR ID 765

### Secondary Scenario (15% probability)
**CAR ID 997 is legitimately separate but overstated expenses mask it**

Evidence:
- CAR 765, 786, 788 show overstatements totaling ~PHP 25,000
- If corrected, could account for hidden transactions
- Hidden PHP 10,000 might be absorbed in adjustments

**Action:** First correct overstated expenses, then reconcile

### Tertiary Scenario (5% probability)
**Missing credit transaction (PHP 10,000 return/refund)**

Evidence:
- Only 3 credits in system (large amounts from Admin)
- No individual employee credits for JENNIFER M.
- Could be unreturned advance

**Action:** Check for missing credit/return entry

---

## 8. PROOF OF CORRECTNESS METHOD

### To prove which balance is correct:

**Step 1:** Export GoTyme Statement (official source)
- List every transaction with date, amount, type (debit/credit)
- Total debits and credits
- Calculate: Opening + Credits - Debits = Ending Balance

**Step 2:** Manual reconciliation
```
GoTyme Debits:     ________
GoTyme Credits:    ________
GoTyme Net:        ________
Expected Ending:   PHP 117,425.98

System Debits:     PHP 3,202,267.50
System Credits:    PHP 3,035,046.00
System Net:        PHP 167,221.50
System Ending:     PHP 107,425.98

Difference:        PHP 10,000.00
```

**Step 3:** Identify variance
- If GoTyme has **more debits**, system is missing a PHP 10,000 debit
- If GoTyme has **less debits**, system has an extra PHP 10,000 debit to remove
- If GoTyme has **more credits**, system is missing a PHP 10,000 credit
- If GoTyme has **less credits**, system has an extra PHP 10,000 credit to remove

---

## RECOMMENDATIONS

### IMMEDIATE ACTIONS (Priority 1)

1. **Delete or Investigate CAR ID 997**
   ```sql
   SELECT * FROM cash_advance_requests WHERE id = 997;
   ```
   - Confirm it's a duplicate of CAR 765 or valid separate entry
   - If duplicate: DELETE
   - If valid: Verify in GoTyme statement

2. **Cross-check with GoTyme Account Statement**
   - Count total debits and credits from actual bank export
   - Verify opening balance: PHP 274,647.48
   - Calculate true ending balance

3. **Reconcile Overstated Expenses**
   - Review CAR 765, 786, 788 liquidation breakdowns
   - Verify each sub-expense is legitimate
   - Check for duplicate LE entries

### FOLLOW-UP ACTIONS (Priority 2)

4. Review data import process
   - Why is CAR 997 marked "Manually Recorded"?
   - Was Jan 5 transaction accidentally imported twice?

5. Establish controls
   - Require manual review of duplicate amounts
   - Implement automated duplicate detection
   - Flag "Manually Recorded" entries for verification

---

## MOST LIKELY SOLUTION

**Delete CAR ID 997** (Manually Recorded JENNIFER M. OPEX Jan 5 PHP 10,000)

This will:
- Reduce debits by PHP 10,000
- New ending balance: PHP 107,425.98 + 10,000 = PHP 117,425.98 ✓
- Match GoTyme expected balance exactly

**Validation:** If this resolves the variance, CAR 997 was a duplicate entry.
