@echo off
SETLOCAL

:: ---- Local Database Info ----
set LOCAL_DB=inventory_management_system
set LOCAL_USER=root
set LOCAL_PASS=

:: ---- Remote Database Info ----
set REMOTE_DB=inventory_management_system
set REMOTE_USER=boughida_nazim
set REMOTE_PASS=Azerty@20252025
set REMOTE_HOST=boughida.com

:: ---- Dump file ----
set DUMP_FILE=dump.sql

:: ---- Paths (adjust if MySQL is not in PATH) ----
set MYSQLDUMP=mysqldump
set MYSQL=mysql

echo.
echo Dumping local database...
%MYSQLDUMP% -u %LOCAL_USER% %LOCAL_DB% > %DUMP_FILE%

IF ERRORLEVEL 1 (
    echo Failed to dump local database.
    goto END
)

echo Local database dumped successfully.

echo.
echo Importing dump into remote database...
%MYSQL% -h %REMOTE_HOST% -u %REMOTE_USER% -p%REMOTE_PASS% %REMOTE_DB% < %DUMP_FILE%

IF ERRORLEVEL 1 (
    echo Failed to import into remote database.
    goto END
)

echo Remote database updated successfully.

:: Optional cleanup
del %DUMP_FILE%

:END
echo.
pause
