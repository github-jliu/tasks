/* ---------------------------------------------------------------------- */
/* Script generated with: DeZign for Databases v6.1.3                     */
/* Target DBMS:           Oracle 10g                                      */
/* Project file:          Project1.dez                                    */
/* Project name:                                                          */
/* Author:                                                                */
/* Script type:           Database creation script                        */
/* Created on:            2011-12-27 18:35                                */
/* ---------------------------------------------------------------------- */


/* ---------------------------------------------------------------------- */
/* Sequences                                                              */
/* ---------------------------------------------------------------------- */

CREATE SEQUENCE TASK_SEQ
    START WITH 1
    INCREMENT BY 1
    NOMINVALUE
    NOMAXVALUE
    nocycle
    noorder;

CREATE SEQUENCE TASK_LIST_SEQ
    START WITH 1
    INCREMENT BY 1
    NOMINVALUE
    NOMAXVALUE
    nocycle
    noorder;

CREATE SEQUENCE GOAL_SEQ
    START WITH 1
    INCREMENT BY 1
    NOMINVALUE
    NOMAXVALUE
    nocycle
    noorder;

CREATE SEQUENCE USER_ACCOUNT_SEQ
    START WITH 1
    INCREMENT BY 1
    NOMINVALUE
    NOMAXVALUE
    nocycle
    noorder;

/* ---------------------------------------------------------------------- */
/* Tables                                                                 */
/* ---------------------------------------------------------------------- */

/* ---------------------------------------------------------------------- */
/* Add table "USER_ACCOUNT"                                               */
/* ---------------------------------------------------------------------- */

CREATE TABLE USER_ACCOUNT (
    USER_ID INTEGER CONSTRAINT NN_USER_ACCOUNT_USER_ID NOT NULL,
    EMAIL VARCHAR2(100) CONSTRAINT NN_USER_ACCOUNT_EMAIL NOT NULL,
    FIRST_NAME VARCHAR2(200) CONSTRAINT NN_USER_ACCOUNT_FIRST_NAME NOT NULL,
    LAST_NAME VARCHAR2(200) CONSTRAINT NN_USER_ACCOUNT_LAST_NAME NOT NULL,
    CONSTRAINT PK_USER_ACCOUNT PRIMARY KEY (USER_ID)
);

/* ---------------------------------------------------------------------- */
/* Add table "TASK"                                                       */
/* ---------------------------------------------------------------------- */

CREATE TABLE TASK (
    TASK_ID INTEGER CONSTRAINT NN_TASK_TASK_ID NOT NULL,
    TASK_DESC VARCHAR2(500) CONSTRAINT NN_TASK_TASK_DESC NOT NULL,
    ASSIGNED_TO_USER_ID INTEGER,
    CREATED_BY_USER_ID INTEGER CONSTRAINT NN_TASK_CREATED_BY_USER_ID NOT NULL,
    CURRENT_STATUS_ID INTEGER CONSTRAINT NN_TASK_CURRENT_STATUS_ID NOT NULL,
    LAST_UPDATED_BY_USER_ID INTEGER,
    LIST_ID INTEGER NOT NULL,
    DUE_DATE DATE,
    CONSTRAINT PK_TASK PRIMARY KEY (TASK_ID)
);

/* ---------------------------------------------------------------------- */
/* Add table "TASK_LIST"                                                  */
/* ---------------------------------------------------------------------- */

CREATE TABLE TASK_LIST (
    LIST_ID INTEGER CONSTRAINT NN_TASK_LIST_LIST_ID NOT NULL,
    LIST_NAME VARCHAR2(50) CONSTRAINT NN_TASK_LIST_LIST_NAME NOT NULL,
    CONSTRAINT PK_TASK_LIST PRIMARY KEY (LIST_ID)
);

/* ---------------------------------------------------------------------- */
/* Add table "TASK_DEPENDENCY"                                            */
/* ---------------------------------------------------------------------- */

CREATE TABLE TASK_DEPENDENCY (
    NEXT_TASK_ID INTEGER CONSTRAINT NN_TASKDEP_NEXTTASKID NOT NULL,
    NEEDS_PRECEDING_TASK_ID INTEGER CONSTRAINT NN_TASKDEP_PRECEDTASKID NOT NULL,
    RELATIONSHIP_TYPE_ID INTEGER CONSTRAINT NN_TASKDEP_RELTYPEID NOT NULL,
    CONSTRAINT PK_TASK_DEPENDENCY PRIMARY KEY (NEXT_TASK_ID, NEEDS_PRECEDING_TASK_ID, RELATIONSHIP_TYPE_ID)
);

/* ---------------------------------------------------------------------- */
/* Foreign key constraints                                                */
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
