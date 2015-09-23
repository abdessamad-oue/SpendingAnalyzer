
-- initial data for category table
 
INSERT INTO category(id, code, wording, label) 
    VALUES  (1, 'CC',       'credit card',      'CREDIT CARD') , 
            (2, 'LEVY',     'levy from this account (prelevement)',       'LEVY'), 
            (3, 'TRANSFER', 'transfer to other account (virement)',    'TRANSFER'),
            (4, 'CASH'  ,   'cash withdrawal',  'CASH'),
            (5, 'CHE'   ,   'bank check',       'BANK CHECK'),
            (6, 'RE_CHE',   'bank check remitter','BANK RE CHECK'),
            (7, 'BANK_CHARGE',  'bank charge',  'BANK CHARGE');

-- type de compte 
INSERT INTO account_type(id, code, label)
    VALUES  (1, 'DEPOSIT', 'deposit account (depot)') ,
            (2, 'SAVINGS', 'Savings account (epargne)');
