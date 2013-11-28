/* ---------------------------------------------------------------------- */
/* Script generated with: DeZign for Databases v6.1.3                     */
/* Target DBMS:           Oracle 10g                                      */
/* Project file:          Project1.dez                                    */
/* Project name:                                                          */
/* Author:                                                                */
/* Script type:           Alter database script                           */
/* Created on:            2012-01-02 23:48                                */
/* ---------------------------------------------------------------------- */


/* ---------------------------------------------------------------------- */
/* Add sequences                                                          */
/* ---------------------------------------------------------------------- */

CREATE SEQUENCE TASK_STATUS_UPDATE_SEQ
    START WITH 1
    INCREMENT BY 1
    NOMINVALUE
    NOMAXVALUE
    nocycle
    noorder;

/* ---------------------------------------------------------------------- */
/* Add table "TASK_STATUS_UPDATE"                                         */
/* ---------------------------------------------------------------------- */

CREATE TABLE TASK_STATUS_UPDATE (
    TASK_STATUS_UPDATE_ID INTEGER CONSTRAINT NN_TASKSTATUPDT_TASKSTATUPDTID NOT NULL,
    TASK_ID INTEGER CONSTRAINT NN_TASK_STATUS_UPDATE_TASK_ID NOT NULL,
    STATUS_UPDATE_DATE DATE CONSTRAINT NN_TASKSTATUPDT_STATUPDTDATE NOT NULL,
    STATUS_ID INTEGER CONSTRAINT NN_TASKSTATUPDT_STATUSID NOT NULL,
    COMMENTS VARCHAR2(4000),
    CONSTRAINT PK_TASK_STATUS_UPDATE PRIMARY KEY (TASK_STATUS_UPDATE_ID)
);

/* ---------------------------------------------------------------------- */
/* Add foreign key constraints                                            */
/* ---------------------------------------------------------------------- */

ALTER TABLE TASK_STATUS_UPDATE ADD CONSTRAINT TASK_TASK_STATUS_UPDATE 
    FOREIGN KEY (TASK_ID) REFERENCES TASK (TASK_ID);
