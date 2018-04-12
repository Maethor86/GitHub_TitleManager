


SET NOCOUNT ON;

BEGIN TRY
	BEGIN TRANSACTION	

	IF EXISTS(SELECT DB_NAME() WHERE DB_NAME() NOT IN ('$(DBNAME)')) USE $(DBNAME);

	SET ANSI_NULLS ON
	SET QUOTED_IDENTIFIER ON

	DECLARE @DateTimeStamp datetime2(7) = SYSDATETIME();

	PRINT 'Renaming foreign key constraint FK_Errors_Web_Users to FK_Errors_Users in [dbo].[Errors]...';

	EXEC sp_rename N'[dbo].[FK_Errors_Web_Users]', N'FK_Errors_Users';

	PRINT 'Renaming primary key constraint PK_Web_Logins to PK_Logins in [dbo].[Logins]...';

	EXEC sp_rename N'[dbo].[Logins].[PK_Web_Logins]', N'PK_Logins';

	PRINT 'Renaming foreign key constraint FK_Web_Logins_Web_Users to FK_Logins_Users in [dbo].[Logins]...';

	EXEC sp_rename N'[dbo].[FK_Web_Logins_Web_Users]', N'FK_Logins_Users';

	PRINT 'Renaming foreign key constraint FK_Missingmovies_Web_Users to FK_Missingmovies_Users in [dbo].[Missingmovies]...';

	EXEC sp_rename N'[dbo].[FK_Missingmovies_Web_Users]', N'FK_Missingmovies_Users';

	PRINT 'Renaming foreign key constraint FK_Movieloans_Web_Users to FK_Movieloans_Users in [dbo].[Movieloans]...';

	EXEC sp_rename N'[dbo].[FK_Movieloans_Web_Users]', N'FK_Movieloans_Users';

	PRINT 'Adding default values to [DateTimeCreated], and to the new columns [DateTimeLastModified] and [LastModifiedByUser] to table [dbo].[Movies]...'

	ALTER TABLE [dbo].[Movies] ADD CONSTRAINT DF_movies_datetimecreated DEFAULT SYSDATETIME() FOR [DateTimeCreated];

	ALTER TABLE [dbo].[Movies]
		ADD 
			[DateTimeLastModified] [datetime2](7) NOT NULL CONSTRAINT DF_movies_datetimelastmodified DEFAULT SYSDATETIME(),
			[LastModifiedByUser] [int] NOT NULL;

	PRINT 'Renaming foreign key constraint FK_Movies_Web_Users to FK_Movies_Users in [dbo].[Movies]...';

	EXEC sp_rename N'[dbo].[FK_Movies_Web_Users]', N'FK_Movies_Users';

	PRINT 'Renaming foreign key constraint FK_Movies_Web_Users1 to FK_Movies_Users1 in [dbo].[Movies]...';

	EXEC sp_rename N'[dbo].[FK_Movies_Web_Users1]', N'FK_Movies_Users1';

	PRINT 'Rename default constraint for [CreatedByUser] in [dbo].[Movies] to DF_movies_createdbyuser...';

	EXEC sp_rename N'[dbo].[DF_Movies_Createdbyuser]', N'DF_movies_createdbyuser', N'OBJECT';

	PRINT 'Rename default constraint for [IMDBID] in [dbo].[Movies] to DF_movies_imdbid...';

	EXEC sp_rename N'[dbo].[DF_Movies_IMDBID]', N'DF_movies_imdbid', N'OBJECT';

	PRINT 'Rename default constraint for [IMDBRating] in [dbo].[Movies] to DF_movies_imdbrating...';

	EXEC sp_rename N'[dbo].[DF_Movies_IMDBRating]', N'DF_movies_imdbrating', N'OBJECT';

	PRINT 'Rename default constraint for [IMDBVotes] in [dbo].[Movies] to DF_movies_imdbvotes...';

	EXEC sp_rename N'[dbo].[DF_Movies_IMDBVotes]', N'DF_movies_imdbvotes', N'OBJECT';

	PRINT 'Rename default constraint for [MoviestatusID] in [dbo].[Movies] to DF_movies_moviestatusid...';

	EXEC sp_rename N'[dbo].[DF_Movies_MoviestatusID]', N'DF_movies_moviestatusid', N'OBJECT';

	PRINT 'Rename default constraint for [Plot] in [dbo].[Movies] to DF_movies_plot...';

	EXEC sp_rename N'[dbo].[DF_Movies_Plot]', N'DF_movies_plot', N'OBJECT';

	PRINT 'Rename default constraint for [QualityID] in [dbo].[Movies] to DF_movies_qualityid...';

	EXEC sp_rename N'[dbo].[DF_Movies_QualityID]', N'DF_movies_qualityid', N'OBJECT';

	PRINT 'Rename default constraint for [ReleasedYear] in [dbo].[Movies] to DF_movies_releasedyear...';

	EXEC sp_rename N'[dbo].[DF_Movies_ReleasedYear]', N'DF_movies_releasedyear', N'OBJECT';
	
	PRINT 'Rename default constraint for [RunningTime] in [dbo].[Movies] to DF_movies_runningtime...';

	EXEC sp_rename N'[dbo].[DF_Movies_RunningTime]', N'DF_movies_runningtime', N'OBJECT';

	PRINT 'Adding default values to [DateTimeCreated] to table [dbo].[Posters]...'

	ALTER TABLE [dbo].[Posters] ADD CONSTRAINT DF_posters_datetimecreated DEFAULT SYSDATETIME() FOR [DateTimeCreated];

	PRINT 'Renaming foreign key constraint FK_Posters_Web_Users to FK_Posters_Users in [dbo].[Posters]...';

	EXEC sp_rename N'[dbo].[FK_Posters_Web_Users]', N'FK_Posters_Users';

	PRINT 'Rename default constraint for [DateTimeCreated] in [dbo].[SchemaChanges] to DF_schemachanges_datetimeapplied...';

	EXEC sp_rename N'[dbo].[DF_SchemaChanges_DateTimeApplied]', N'DF_schemachanges_datetimeapplied', N'OBJECT';

	PRINT 'Renaming table [dbo].[Web_Users] to [dbo].[Users]...';

	EXEC sp_rename N'[dbo].[Web_Users]', N'Users';
	
	PRINT 'Renaming primary key constraint PK_user to PK_Users in [dbo].[Users]...';

	EXEC sp_rename N'[dbo].[Users].[PK_user]', N'PK_Users';

	PRINT 'Rename default constraint for [DateTimeCreated] in [dbo].[Users] to DF_users_datetimecreated...';

	EXEC sp_rename N'[dbo].[DF_Web_Users_DateCreated]', N'DF_users_datetimecreated', N'OBJECT';

	PRINT 'Rename default constraint for [UserRoleID] in [dbo].[Users] to DF_users_userroleid...';

	EXEC sp_rename N'[dbo].[DF_Web_User_UserRole]', N'DF_users_userroleid', N'OBJECT';

	PRINT 'Change default constraint DF_users_datetimecreated in [dbo].[Users]...';

	ALTER TABLE [dbo].[Users] DROP CONSTRAINT DF_users_datetimecreated;
	ALTER TABLE [dbo].[Users] ADD CONSTRAINT DF_users_datetimecreated DEFAULT SYSDATETIME() FOR [DateTimeCreated];

	PRINT 'Renaming foreign key constraint FK_Web_Users_Web_UserRoles to FK_Users_Web_UserRoles in [dbo].[Users]...';

	EXEC sp_rename N'[dbo].[FK_Web_Users_Web_UserRoles]', N'FK_Users_Web_UserRoles';

	PRINT 'Renaming index IX_Web_Users to IX_Users_Username in [dbo].[Users]...';

	EXEC sp_rename N'[dbo].[Users].[IX_Web_Users]', N'IX_Users_Username';
	
	PRINT 'Renaming primary key constraint PK_Web.Page to PK_Web_Pages in [dbo].[Web_Pages]...';

	EXEC sp_rename N'[dbo].[Web_Pages].[PK_Web.Page]', N'PK_Web_Pages';

	PRINT 'Renaming primary key constraint PK_Web.Subject to PK_Web_Subjects in [dbo].[Web_Subjects]...';

	EXEC sp_rename N'[dbo].[Web_Subjects].[PK_Web.Subject]', N'PK_Web_Subjects';

	---

	PRINT 'Updating table [dbo].[SchemaChanges] with SchemaChangeNumber...'

	DECLARE @MajorReleaseNumber nvarchar(2) = N'00';
	DECLARE @MinorReleaseNumber nvarchar(2) = N'00';
	DECLARE @PointReleaseNumber nvarchar(4) = N'0003';
	DECLARE @ScriptDescription nvarchar(500) = 'Renames [dbo].[Web_Users] to [dbo].[Users]. Adds last modified columns to [dbo].[Movies]. Also renames DFs, PKs, FKs and IXs to adhere to the same naming convention.';
	INSERT [dbo].[SchemaChanges] ([MajorReleaseNumber], [MinorReleaseNumber], [PointReleaseNumber], [ScriptDescription], [DateTimeApplied]) VALUES (@MajorReleaseNumber, @MinorReleaseNumber, @PointReleaseNumber, @ScriptDescription, @DateTimeStamp);


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
