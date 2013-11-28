/* ---------------------------------------------------------------------- */
/* Script generated with: DeZign for Databases v6.1.3                     */
/* Target DBMS:           Oracle 10g                                      */
/* Project file:          tasks_db.dez                                    */
/* Project name:                                                          */
/* Author:                                                                */
/* Script type:           Alter database script                           */
/* Created on:            2012-01-03 10:21                                */
/* ---------------------------------------------------------------------- */


/* ---------------------------------------------------------------------- */
/* Drop foreign key constraints                                           */
/* ---------------------------------------------------------------------- */

ALTER TABLE TASK DROP CONSTRAINT USER_ACCOUNT_TASK_ASSIGNED_TO;

ALTER TABLE TASK DROP CONSTRAINT USER_ACCOUNT_TASK_CREATED_BY;

ALTER TABLE TASK DROP CONSTRAINT USERACT_TASK_LAST_UPDATED;

ALTER TABLE TASK DROP CONSTRAINT TASK_LIST_TASK;

ALTER TABLE TASK_DEPENDENCY DROP CONSTRAINT TASK_TASK_DEPENDENCY_NEXT_TASK;

ALTER TABLE TASK_DEPENDENCY DROP CONSTRAINT TASK_TASK_DEPENDENCY_REQ_TASK;

ALTER TABLE TASK_STATUS_UPDATE DROP CONSTRAINT TASK_TASK_STATUS_UPDATE;

/* ---------------------------------------------------------------------- */
/* Modify table "TASK"                                                    */
/* ---------------------------------------------------------------------- */

ALTER TABLE TASK ADD
    COMMENTS VARCHAR2(4000);

/* ---------------------------------------------------------------------- */
/* Add foreign key constraints                                            */
/* ---------------------------------------------------------------------- */

ALTER TABLE TASK ADD CONSTRAINT USER_ACCOUNT_TASK_ASSIGNED_TO 
    FOREIGN KEY (ASSIGNED_TO_USER_ID) REFERENCES USER_ACCOUNT (USER_ID);

ALTER TABLE TASK ADD CONSTRAINT USER_ACCOUNT_TASK_CREATED_BY 
    FOREIGN KEY (CREATED_BY_USER_ID) REFERENCES USER_ACCOUNT (USER_ID);

ALTER TABLE TASK ADD CONSTRAINT USERACT_TASK_LAST_UPDATED 
    FOREIGN KEY (LAST_UPDATED_BY_USER_ID) REFERENCES USER_ACCOUNT (USER_ID);

ALTER TABLE TASK ADD CONSTRAINT TASK_LIST_TASK 
    FOREIGN KEY (LIST_ID) REFERENCES TASK_LIST (LIST_ID) ON DELETE CASCADE;

ALTER TABLE TASK_DEPENDENCY ADD CONSTRAINT TASK_TASK_DEPENDENCY_NEXT_TASK 
    FOREIGN KEY (NEXT_TASK_ID) REFERENCES TASK (TASK_ID) ON DELETE CASCADE;

ALTER TABLE TASK_DEPENDENCY ADD CONSTRAINT TASK_TASK_DEPENDENCY_REQ_TASK 
    FOREIGN KEY (NEEDS_PRECEDING_TASK_ID) REFERENCES TASK (TASK_ID) ON DELETE CASCADE;

ALTER TABLE TASK_STATUS_UPDATE ADD CONSTRAINT TASK_TASK_STATUS_UPDATE 
    FOREIGN KEY (TASK_ID) REFERENCES TASK (TASK_ID);
