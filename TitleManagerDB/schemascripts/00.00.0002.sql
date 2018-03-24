


SET NOCOUNT ON;

BEGIN TRY
	BEGIN TRANSACTION	

	IF EXISTS(SELECT DB_NAME() WHERE DB_NAME() NOT IN ('$(DBNAME)')) USE $(DBNAME);

	SET ANSI_NULLS ON
	SET QUOTED_IDENTIFIER ON

	PRINT 'Renaming table [dbo].[Web_Logins] to [dbo].[Logins]...'

	EXEC sp_rename '[dbo].[Web_Logins]', 'Logins';

	PRINT 'Renaming column [Web_LoginID] in [dbo].[Logins] to [LoginID]...'
	
	EXEC sp_rename '[dbo].[Logins].[Web_LoginID]', 'LoginID', 'COLUMN';

	
	
	PRINT 'Updating table [dbo].[SchemaChanges] with SchemaChangeNumber...'

	DECLARE @MajorReleaseNumber nvarchar(2) = N'00';
	DECLARE @MinorReleaseNumber nvarchar(2) = N'00';
	DECLARE @PointReleaseNumber nvarchar(4) = N'0002';
	DECLARE @ScriptDescription nvarchar(500) = 'Renames table [Web_Logins] to [Logins]. Renames column [Web_LoginID] in table [Logins] to [LoginID].';
	INSERT [dbo].[SchemaChanges] ([MajorReleaseNumber], [MinorReleaseNumber], [PointReleaseNumber], [ScriptDescription], [DateTimeApplied]) VALUES (@MajorReleaseNumber, @MinorReleaseNumber, @PointReleaseNumber, @ScriptDescription, SYSDATETIME());

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