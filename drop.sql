/* ---------------------------------------------------------------------- */
/* Script generated with: DeZign for Databases v6.1.3                     */
/* Target DBMS:           Oracle 10g                                      */
/* Project file:          Project1.dez                                    */
/* Project name:                                                          */
/* Author:                                                                */
/* Script type:           Database drop script                            */
/* Created on:            2011-12-27 18:35                                */
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

/* ---------------------------------------------------------------------- */
/* Drop table "TASK_DEPENDENCY"                                           */
/* ---------------------------------------------------------------------- */

/* Drop constraints */

ALTER TABLE TASK_DEPENDENCY DROP CONSTRAINT NN_TASKDEP_NEXTTASKID;

ALTER TABLE TASK_DEPENDENCY DROP CONSTRAINT NN_TASKDEP_PRECEDTASKID;

ALTER TABLE TASK_DEPENDENCY DROP CONSTRAINT NN_TASKDEP_RELTYPEID;

ALTER TABLE TASK_DEPENDENCY DROP CONSTRAINT PK_TASK_DEPENDENCY;

/* Drop table */

DROP TABLE TASK_DEPENDENCY;

/* ---------------------------------------------------------------------- */
/* Drop table "TASK"                                                      */
/* ---------------------------------------------------------------------- */

/* Drop constraints */

ALTER TABLE TASK DROP CONSTRAINT NN_TASK_TASK_ID;

ALTER TABLE TASK DROP CONSTRAINT NN_TASK_TASK_DESC;

ALTER TABLE TASK DROP CONSTRAINT NN_TASK_CREATED_BY_USER_ID;

ALTER TABLE TASK DROP CONSTRAINT NN_TASK_CURRENT_STATUS_ID;

ALTER TABLE TASK DROP CONSTRAINT PK_TASK;

/* Drop table */

DROP TABLE TASK;

/* ---------------------------------------------------------------------- */
/* Drop table "TASK_LIST"                                                 */
/* ---------------------------------------------------------------------- */

/* Drop constraints */

ALTER TABLE TASK_LIST DROP CONSTRAINT NN_TASK_LIST_LIST_ID;

ALTER TABLE TASK_LIST DROP CONSTRAINT NN_TASK_LIST_LIST_NAME;

ALTER TABLE TASK_LIST DROP CONSTRAINT PK_TASK_LIST;

/* Drop table */

DROP TABLE TASK_LIST;

/* ---------------------------------------------------------------------- */
/* Drop table "USER_ACCOUNT"                                              */
/* ---------------------------------------------------------------------- */

/* Drop constraints */

ALTER TABLE USER_ACCOUNT DROP CONSTRAINT NN_USER_ACCOUNT_USER_ID;

ALTER TABLE USER_ACCOUNT DROP CONSTRAINT NN_USER_ACCOUNT_EMAIL;

ALTER TABLE USER_ACCOUNT DROP CONSTRAINT NN_USER_ACCOUNT_FIRST_NAME;

ALTER TABLE USER_ACCOUNT DROP CONSTRAINT NN_USER_ACCOUNT_LAST_NAME;

ALTER TABLE USER_ACCOUNT DROP CONSTRAINT PK_USER_ACCOUNT;

/* Drop table */

DROP TABLE USER_ACCOUNT;

/* ---------------------------------------------------------------------- */
/* Drop sequences                                                         */
/* ---------------------------------------------------------------------- */

DROP SEQUENCE TASK_SEQ;

DROP SEQUENCE TASK_LIST_SEQ;

DROP SEQUENCE GOAL_SEQ;

DROP SEQUENCE USER_ACCOUNT_SEQ;
