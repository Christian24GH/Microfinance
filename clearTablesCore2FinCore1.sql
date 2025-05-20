SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `client_documents`;
DROP TABLE IF EXISTS `client_employment`;
DROP TABLE IF EXISTS `client_financial_info`;
DROP TABLE IF EXISTS `client_info`;
DROP TABLE IF EXISTS `client_references`;
DROP TABLE IF EXISTS `loan_info`;
DROP TABLE IF EXISTS `savings_tracking`;
DROP TABLE IF EXISTS `loan_info`;
DROP TABLE IF EXISTS `email_logs`;
DROP TABLE IF EXISTS `disb_repayment_tbl`;
DROP TABLE IF EXISTS `disbursement_tbl`;
DROP TABLE IF EXISTS `client_references`;
DROP TABLE IF EXISTS `client_info`;
DROP TABLE IF EXISTS `client_financial_info`;
DROP TABLE IF EXISTS `client_employment`;
DROP TABLE IF EXISTS `client_document`;

DROP TABLE IF EXISTS `ap_funder_repayment_tbl`;
DROP TABLE IF EXISTS `ap_funding_source_tbl`;
DROP TABLE IF EXISTS `ap_liability_tbl`;
DROP TABLE IF EXISTS `ap_report_tbl`;

DROP TABLE IF EXISTS `ar_disb_tracking_tbl`;
DROP TABLE IF EXISTS `ar_loan_account_tbl`;
DROP TABLE IF EXISTS `ar_repayment_management_tbl`;
DROP TABLE IF EXISTS `ar_report_tbl`;

DROP TABLE IF EXISTS `bm_budget_adjustment_tbl`;
DROP TABLE IF EXISTS `bm_budget_monitoring_tbl`;
DROP TABLE IF EXISTS `bm_budget_planning_tbl`;
DROP TABLE IF EXISTS `bm_report_tbl`;

DROP TABLE IF EXISTS `bm_budget_adjustment_tbl`;
DROP TABLE IF EXISTS `bm_budget_monitoring_tbl`;
DROP TABLE IF EXISTS `bm_report_tbl`;
DROP TABLE IF EXISTS `bm_budget_planning_tbl`;

DROP TABLE IF EXISTS `collection_account_payment_tbl`;
DROP TABLE IF EXISTS `collection_ar_tbl`;
DROP TABLE IF EXISTS `collection_management_tbl`;
DROP TABLE IF EXISTS `collection_report_tbl`;

DROP TABLE IF EXISTS `disbursement_tbl`;
DROP TABLE IF EXISTS `disb_loan_tbl`;
DROP TABLE IF EXISTS `disb_repayment_tbl`;
DROP TABLE IF EXISTS `disb_report_tbl`;


DROP TABLE IF EXISTS `general_ledger_tbl`;
DROP TABLE IF EXISTS `gl_transaction_tbl`;
DROP TABLE IF EXISTS `gl_statement_tbl`;
DROP TABLE IF EXISTS `gl_report_tbl`;
DROP TABLE IF EXISTS `gl_chart_of_account_tbl`;
DROP TABLE IF EXISTS `gl_book_keeper_tbl`;
SET FOREIGN_KEY_CHECKS = 1;