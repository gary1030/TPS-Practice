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