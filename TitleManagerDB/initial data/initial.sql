
--PRINT 'Script ''$(SCRIPTNAME)'' starting, with input:';
--PRINT 'DBNAME=''$(DBNAME)'',';
--PRINT 'FILENAME=''$(FILENAME)'',';
--PRINT 'DIRECTORY=''$(DIRECTORY)''.' + CHAR(13) + CHAR(10)
--GO


SET NOCOUNT ON;
GO

IF EXISTS(SELECT DB_NAME() WHERE DB_NAME() NOT IN ('$(DBNAME)')) USE $(DBNAME);
GO

BEGIN TRY
	BEGIN TRANSACTION

		DECLARE @directory nvarchar(100);
		DECLARE @filename nvarchar(50);
		DECLARE @destination_table nvarchar(50);
		SET @directory = '$(DIRECTORY)';
		SET @filename = '$(FILENAME)';
		PRINT 'Loading file ''' + @filename + ''' from directory...';

		SELECT @destination_table = LEFT(@filename,LEN(@filename)-4)
		PRINT 'Populating table ''' + @destination_table + '''...'

		DECLARE @sql nvarchar(max)
		SET @sql = '
		BULK INSERT dbo.' + @destination_table + '
			FROM ''' + @directory + @filename + '''
			WITH
			(
				FIRSTROW = 2,
				FIELDTERMINATOR = ''|'',
				ROWTERMINATOR = ''\n''
			)'

		--PRINT @sql;
		EXEC(@sql)
		PRINT 'Table ''' + @destination_table + ''' populated.';

	COMMIT TRANSACTION
END TRY
BEGIN CATCH
	IF (@@TRANCOUNT > 0) ROLLBACK TRANSACTION
	DECLARE @ERROR_MSG nvarchar(2048) = error_message()
	DECLARE @ERROR_SEVERITY nvarchar(2048) = error_severity()
	DECLARE @ERROR_STATE nvarchar(2048) = error_state()
	RAISERROR (@ERROR_MSG, @ERROR_SEVERITY, @ERROR_STATE)
END CATCH
GO














SET NOCOUNT OFF;
GO

/*
BULK INSERT dbo.Movies
    FROM 'C:\Maethor\Projects\TitleManager\TitleManagerDB\want to have\initial data\movies.csv'
    WITH
    (
        FIRSTROW = 2,
        FIELDTERMINATOR = '|',
        ROWTERMINATOR = '\n'
    )
GO


BULK INSERT dbo.Posters
    FROM 'C:\Maethor\Projects\TitleManager\TitleManagerDB\want to have\initial data\posters.csv'
    WITH
    (
        FIRSTROW = 2,
        FIELDTERMINATOR = '|',
        ROWTERMINATOR = '\n'
    )
GO

*/


--PRINT CHAR(13) + CHAR(10) + 'Script ''$(SCRIPTNAME)'' finished.'
--GO