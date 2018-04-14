
@ECHO OFF
GOTO :SET_VARIABLES

:: -----------------------------------------------ABOUT----------------------------------------------------------------------------
:ABOUT_SCRIPT

ECHO 	--- About Script ---
ECHO 	Author:		Maethor
ECHO 	Date created:	180302 15:11, by H710-MAETHOR\Maethor
ECHO 	Last updated: 	%BAT_LASTUPDATED%, by %USER%
ECHO 	---- End About -----
ECHO.
GOTO :USER_INPUT

:: -----------------------------------------------VARIABLES ETC---------------------------------------------------------------------
:SET_VARIABLES

SET "BASELINE_FILE=00.00.0000.sql"
SET "NEXT_SCRIPT_FILE=00.00.0001.sql"
SET "NEXTNEXT_SCRIPT_FILE=00.00.0002.sql"
SET "NEXTNEXTNEXT_SCRIPT_FILE=00.00.0003.sql"
SET "NEXTNEXTNEXTNEXT_SCRIPT_FILE=00.00.0004.sql"
SET "INITIALDATA_FILE=initial.sql"

SET "ROOT_DIR=C:\Maethor\Projects\TitleManager\"
SET "SCHEMA_DIR=C:\Maethor\Projects\TitleManager\TitleManagerDB\schemascripts\"
SET "DATA_DIR=C:\Maethor\Projects\TitleManager\TitleManagerDB\initial data\"
SET "DATA_DIR_FIRST=%DATA_DIR%load\first\"
SET "DATA_DIR_SECOND=%DATA_DIR%load\second\"
SET "DATA_DIR_THIRD=%DATA_DIR%load\third\"
SET "LOG_DIR=C:\Maethor\Projects\TitleManager\TitleManagerDB\logs\"
SET "ERROR_LOG_DIR="%LOG_DIR%errors\""

SET "POSTER_DIR="%ROOT_DIR%\TitleManagerWeb\public\images\posters\""

SET "EXECUTION_FILE=execution.log"
SET "DATAINIT_FILE=datainit.log"

SET "EXECUTION_LOG="%LOG_DIR%%EXECUTION_FILE%""
SET "DATAINIT_LOG="%LOG_DIR%%DATAINIT_FILE%""

SET "BASELINE_SCRIPT="%SCHEMA_DIR%%BASELINE_FILE%""
SET "NEXT_SCRIPT="%SCHEMA_DIR%%NEXT_SCRIPT_FILE%""
SET "NEXTNEXT_SCRIPT="%SCHEMA_DIR%%NEXTNEXT_SCRIPT_FILE%""
SET "NEXTNEXTNEXT_SCRIPT="%SCHEMA_DIR%%NEXTNEXTNEXT_SCRIPT_FILE%""
SET "NEXTNEXTNEXTNEXT_SCRIPT="%SCHEMA_DIR%%NEXTNEXTNEXTNEXT_SCRIPT_FILE%""
SET "INITIALDATA_SCRIPT="%DATA_DIR%%INITIALDATA_FILE%""
SET "TMP_FILE="%SCHEMA_DIR%tmp.txt""
SET "TMP_FILE2="%SCHEMA_DIR%tmp2.txt""


SET "EXECUTION_ERROR_FILE=error_execution.log"
SET "DATAINIT_ERROR_FILE=error_datainit.log"

SET "EXECUTION_ERROR_LOG="%ERROR_LOG_DIR%%EXECUTION_ERROR_FILE%""
SET "DATAINIT_ERROR_LOG="%ERROR_LOG_DIR%%DATAINIT_ERROR_FILE%""

:: this is so that the progress percentage works
:: %0 is the path to the bat-file itself
:: "%~0 just means the file path to the current batch file, so it means copy the current batch file to NUL
:: the /Z flag just means if anything goes wrong in the copy, restart and try again
:: so the whole thing is "whatever is returned by this copy command, set CR to that" which apparently is a carriage return (and no line feed)
FOR /f %%G IN ('COPY /Z "%~0" NUL') DO (SET "CR=%%G")

FOR %%G IN ("%~0") DO (SET BAT_LASTUPDATED=%%~tG)

SET USER=%USERDOMAIN%\%USERNAME%

IF NOT EXIST "%SCHEMA_DIR%" MKDIR "%SCHEMA_DIR%"
IF NOT EXIST "%DATA_DIR%" MKDIR "%DATA_DIR%"
IF NOT EXIST "%POSTER_DIR%" MKDIR "%POSTER_DIR%"
IF NOT EXIST "%LOG_DIR%" MKDIR "%LOG_DIR%"
IF NOT EXIST "%ERROR_LOG_DIR%" MKDIR "%ERROR_LOG_DIR%"

GOTO :ABOUT_SCRIPT

:: -----------------------------------------------USER INPUT------------------------------------------------------------------------
:USER_INPUT

ECHO This script will install a new database on the system.
SET /p inputDBNAME="Enter the name of the new database: "

GOTO :CREATE_BASELINE

:: -----------------------------------------------THE BAT SCRIPT---------------------------------------------------------------------
:CREATE_BASELINE

:: ---- create database, and database schema, stored procedures, static data
ECHO Baselining...
(sqlcmd -b -S H710-MAETHOR\SQLEXPRESS -i %BASELINE_SCRIPT% -v DBNAME=%inputDBNAME% SCRIPTNAME=%BASELINE_FILE% -o %TMP_FILE%) && (GOTO :BASELINE_SUCCESSFUL) || (GOTO :ERROR_BASELINE)


:BASELINE_SUCCESSFUL
ECHO Baseline successful!
ECHO. >>%EXECUTION_LOG%
ECHO ------------%date% %time%, by %USER%------------ >>%EXECUTION_LOG%
ECHO Database '%inputDBNAME%' successfully baselined!>>%EXECUTION_LOG%
ECHO --- Script messages for %BASELINE_FILE%  --->>%EXECUTION_LOG%
TYPE %TMP_FILE%>>%EXECUTION_LOG%
ECHO --- End of script messages for %BASELINE_FILE% --->>%EXECUTION_LOG%
IF EXIST %TMP_FILE% DEL %TMP_FILE%
(sqlcmd -b -S H710-MAETHOR\SQLEXPRESS -i %NEXT_SCRIPT% -v DBNAME=%inputDBNAME% -o %TMP_FILE%) || (GOTO :ERROR_BASELINE)
(sqlcmd -b -S H710-MAETHOR\SQLEXPRESS -i %NEXTNEXT_SCRIPT% -v DBNAME=%inputDBNAME% -o %TMP_FILE%) || (GOTO :ERROR_BASELINE)
(sqlcmd -b -S H710-MAETHOR\SQLEXPRESS -i %NEXTNEXTNEXT_SCRIPT% -v DBNAME=%inputDBNAME% -o %TMP_FILE%) || (GOTO :ERROR_BASELINE)
(sqlcmd -b -S H710-MAETHOR\SQLEXPRESS -i %NEXTNEXTNEXTNEXT_SCRIPT% -v DBNAME=%inputDBNAME% -o %TMP_FILE%) || (GOTO :ERROR_BASELINE)
GOTO :LOAD_DATA



:: ---- load inital data
:LOAD_DATA

setLocal EnableDelayedExpansion

:: total files to load
SET /a TOTAL_FILES=0
FOR /f "usebackq" %%g IN (`DIR /b "%DATA_DIR_FIRST%*.csv"`) DO (SET /a TOTAL_FILES=TOTAL_FILES+1)
FOR /f "usebackq" %%g IN (`DIR /b "%DATA_DIR_SECOND%*.csv"`) DO (SET /a TOTAL_FILES=TOTAL_FILES+1)
FOR /f "usebackq" %%g IN (`DIR /b "%DATA_DIR_THIRD%*.csv"`) DO (SET /a TOTAL_FILES=TOTAL_FILES+1)

SET /a TOTAL_POSTER_FILES=0
FOR /f "usebackq" %%g IN (`DIR /b "%DATA_DIR%posters\*.*"`) DO (SET /a TOTAL_POSTER_FILES=TOTAL_POSTER_FILES+1)

ECHO Loading data...
FOR /f "usebackq" %%G IN (`DIR /b "%DATA_DIR_FIRST%*.csv"`) DO ((sqlcmd -b -S H710-MAETHOR\SQLEXPRESS -i %INITIALDATA_SCRIPT% -v DBNAME=%inputDBNAME% SCRIPTNAME=%INITIALDATA_FILE% DIRECTORY="%DATA_DIR_FIRST%" FILENAME=%%G -o %TMP_FILE%) && (SET /a COUNT=COUNT+1 & CALL :SHOW_PROGRESS !COUNT! %TOTAL_FILES% & CALL :WRITE_FROM_TEMPFILE) || (GOTO :ERROR_DATALOAD))
FOR /f "usebackq" %%G IN (`DIR /b "%DATA_DIR_SECOND%*.csv"`) DO ((sqlcmd -b -S H710-MAETHOR\SQLEXPRESS -i %INITIALDATA_SCRIPT% -v DBNAME=%inputDBNAME% SCRIPTNAME=%INITIALDATA_FILE% DIRECTORY="%DATA_DIR_SECOND%" FILENAME=%%G -o %TMP_FILE%) && (SET /a COUNT=COUNT+1 & CALL :SHOW_PROGRESS !COUNT! %TOTAL_FILES% & CALL :WRITE_FROM_TEMPFILE) || (GOTO :ERROR_DATALOAD))
FOR /f "usebackq" %%G IN (`DIR /b "%DATA_DIR_THIRD%*.csv"`) DO ((sqlcmd -b -S H710-MAETHOR\SQLEXPRESS -i %INITIALDATA_SCRIPT% -v DBNAME=%inputDBNAME% SCRIPTNAME=%INITIALDATA_FILE% DIRECTORY="%DATA_DIR_THIRD%" FILENAME=%%G -o %TMP_FILE%) && (SET /a COUNT=COUNT+1 & CALL :SHOW_PROGRESS !COUNT! %TOTAL_FILES% & CALL :WRITE_FROM_TEMPFILE) || (GOTO :ERROR_DATALOAD))
FOR /f "usebackq" %%G IN (`DIR /b "%DATA_DIR%posters\*"`) DO ((COPY /Y "%DATA_DIR%posters\%%G" "%POSTER_DIR%%%G" >nul) && (SET /a POSTER_COUNT=POSTER_COUNT+1 & CALL :SHOW_PROGRESS !POSTER_COUNT! %TOTAL_POSTER_FILES%) || (GOTO :ERROR_DATALOAD))
setLocal DisableDelayedExpansion
GOTO :DATALOAD_SUCCESSFUL

:DATALOAD_SUCCESSFUL
ECHO Loading data successful!
ECHO. >>%DATAINIT_LOG%
ECHO ------------%date% %time%, by %USER%------------ >>%DATAINIT_LOG%
ECHO Initializing database with initial data was successful.>>%DATAINIT_LOG%
TYPE %TMP_FILE2%>>%DATAINIT_LOG%
GOTO :QUIT_WITHOUT_ERROR


:QUIT_WITHOUT_ERROR
ECHO.
ECHO Database '%inputDBNAME%' installed successfully, and initialized with inital data!
ECHO.
ECHO See the logfiles for more info:
ECHO 	Execution log: 	%EXECUTION_LOG%
ECHO 	Datainit log: 	%DATAINIT_LOG%
ECHO.
ECHO Success!
ECHO Press any key to exit...
IF EXIST %TMP_FILE% DEL %TMP_FILE%
IF EXIST %TMP_FILE2% DEL %TMP_FILE2%
PAUSE >NUL
EXIT



:: error methods

:ERROR_BASELINE
ECHO.>>%EXECUTION_ERROR_LOG%
ECHO ------------%date% %time%, by %USER%------------ >>%EXECUTION_ERROR_LOG%
ECHO Something went wrong trying to create database with schema.>>%EXECUTION_ERROR_LOG%
ECHO --- Script messages for %BASELINE_FILE%  --->>%EXECUTION_ERROR_LOG%
TYPE %TMP_FILE%>>%EXECUTION_ERROR_LOG%
ECHO --- End of script messages for %BASELINE_FILE% --->>%EXECUTION_ERROR_LOG%
ECHO Baseline not successful.
ECHO.
ECHO Database '%inputDBNAME%' not installed!
ECHO Quitting with error.
GOTO :QUIT_WITH_ERROR

:ERROR_DATALOAD
ECHO.>>%DATAINIT_ERROR_LOG%
ECHO ------------%date% %time%, by %USER%------------ >>%DATAINIT_ERROR_LOG%
ECHO Something went wrong trying to initialize database with initial data.>>%DATAINIT_ERROR_LOG%
ECHO --- Script messages for %INITIALDATA_FILE% --->>%DATAINIT_ERROR_LOG%
TYPE %TMP_FILE%>>%DATAINIT_ERROR_LOG%
ECHO --- End of script messages for %INITIALDATA_FILE% --->>%DATAINIT_ERROR_LOG%
ECHO Loading data not successful.
ECHO.
ECHO Initial data not loaded into the database '%inputDBNAME%'!
ECHO Quitting with error.
GOTO :QUIT_WITH_ERROR


:QUIT_WITH_ERROR
ECHO.
ECHO See the logfiles to see the errors generated by SQLSERVER:
ECHO 	Execution error log: 	%EXECUTION_ERROR_LOG%
ECHO 	Datainit error log: 	%DATAINIT_ERROR_LOG%
ECHO.
ECHO Not success.
ECHO Press any key to exit...
IF EXIST %TMP_FILE% DEL %TMP_FILE%
IF EXIST %TMP_FILE2% DEL %TMP_FILE2%
PAUSE >NUL
EXIT

:: end error methods


:: other methods

:WRITE_FROM_TEMPFILE
ECHO --- Script messages for %INITIALDATA_FILE% --->>%TMP_FILE2%
TYPE %TMP_FILE%>>%TMP_FILE2%
ECHO --- End of script messages for %INITIALDATA_FILE% --->>%TMP_FILE2%
EXIT /b


:SHOW_PROGRESS
setLocal EnableDelayedExpansion
SET current_step=%1
SET total_steps=%2
SET /a "progress=(current_step * 100) / total_steps"

SET /p ".=...............!progress!%%!CR!" <nul

IF !progress! EQU 100 ECHO.

setLocal DisableDelayedExpansion
EXIT /b




:: --------------------------------------------INFO-------------------------------------------------------------------------------


:: some syntax used in this script, from https://ss64.com/nt/syntax-conditional.html

:: if command1 succeeds then execute command2 (IF)
:: command1 && command2

:: Execute command1 and then execute command2 (AND)
:: command1 & command2

:: Execute command2 only if command1 fails (OR)
:: command1 || command2

:: ...