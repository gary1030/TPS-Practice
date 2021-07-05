-- input product id and get the member who bought it before
SELECT member.member_name, member.member_id
FROM member
WHERE member.member_id IN (
    SELECT transaction.member_id
    From transaction
    WHERE transaction.product_id = 1
)
ORDER BY member.member_id;

-- function one (input member id and get all the transaction with the member)
SELECT *
FROM transaction
WHERE transaction.member_id = 3;

--function one (input member id and get transaction sum from each product)
SELECT COUNT(transaction.product_id), SUM(transaction.transaction_price), transaction.product_id
FROM transaction
WHERE member_id = 3
GROUP BY product_id
ORDER BY product_id;


/*function two (input product id and get all the transaction record)*/
SELECT transaction_id, transaction_date, transaction.member_id, transaction_price
FROM transaction
WHERE transaction.product_id = 1;


/*function two (input product id and get transaction sum & times from each member)*/
SELECT transaction.member_id, COUNT(transaction.member_id), SUM(transaction.transaction_price)
FROM transaction
WHERE product_id = 1
GROUP BY member_id
ORDER BY member_id;

/*function three (input member id and output the avaerage daily consumption)
GETDATE: get the time in the computer now (?)
*/
SELECT x.member_id, y.number / x.number AS 'consumption_per_day'
FROM(
    SELECT DATEDIFF('2022-01-01', member.member_date) AS number, member.member_id
    FROM member
) x
JOIN(
    SELECT SUM(transaction_price) AS number , transaction.member_id
    FROM transaction
    GROUP BY transaction.member_id
) y 
ON x.member_id = y.member_id;

/*function four (input product id and output transaction sum & times for different genders)
*/
SELECT member.member_gender, COUNT(transaction.member_id), SUM(transaction.transaction_price)
FROM member
JOIN transaction
ON member.member_id = transaction.member_id
WHERE transaction.product_id = 20
GROUP BY member.member_gender
ORDER BY member.member_gender;

/*function four (input product id and output transaction sum & times for different age level)
*/
SELECT member.member_gender, COUNT(transaction.member_id), SUM(transaction.transaction_price)
FROM member
JOIN transaction
ON member.member_id = transaction.member_id
WHERE transaction.product_id = 20
GROUP BY member.member_gender
ORDER BY member.member_gender;

/*function four (input product id and output transaction sum & times for different age level)
*/
/*Where age level is 0 for age group below 20, 1 for age group 20-30, 2 for age group 30-40, 3 for age group 40 above.*/
SELECT INTERVAL(DATEDIFF('2022-01-01', member.member_BD),7305,10958,14610) AS ageLevel, COUNT(transaction.member_id), SUM(transaction.transaction_price)
FROM member
JOIN transaction
ON member.member_id = transaction.member_id
WHERE transaction.product_id = 1
GROUP BY ageLevel
ORDER BY ageLevel;
/*repeated counting for the same member*/