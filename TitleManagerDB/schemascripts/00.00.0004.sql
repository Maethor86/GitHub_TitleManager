


SET NOCOUNT ON;

BEGIN TRY
	BEGIN TRANSACTION	

	IF EXISTS(SELECT DB_NAME() WHERE DB_NAME() NOT IN ('$(DBNAME)')) USE $(DBNAME);

	SET ANSI_NULLS ON
	SET QUOTED_IDENTIFIER ON

	DECLARE @DateTimeStamp datetime2(7) = SYSDATETIME();

	---


	PRINT 'Altering datatypes of some columns in [dbo].[Movies] from the deprecated ''text'' to ''nvarchar(max)''...';

	ALTER TABLE [dbo].[Movies]
		DROP CONSTRAINT [DF_movies_plot];

	ALTER TABLE [dbo].[Movies]
		ALTER COLUMN Plot [nvarchar](max) NOT NULL;

	ALTER TABLE [dbo].[Movies] ADD CONSTRAINT [DF_movies_plot] DEFAULT 'No plot entered.' FOR [Plot];

	ALTER TABLE [dbo].[Movies]
		ALTER COLUMN PlotSummary [nvarchar](max) NOT NULL;

	ALTER TABLE [dbo].[Movies] ADD CONSTRAINT [DF_movies_plotsummary] DEFAULT 'No plot summary entered.' FOR [PlotSummary];






	PRINT 'Creating schema [hist]...';

	EXEC('CREATE SCHEMA [hist]');

	PRINT 'Creating table [hist].[Errors]...'

/*
	CREATE TABLE [hist].[Errors](
		[HistoryErrorID] [int] IDENTITY(1,1) NOT NULL,
		[HostName] [nvarchar](128) NOT NULL,
		[HostID] [nvarchar](10) NOT NULL,
		[ClientNetAddress] [nvarchar](48) NOT NULL,
		[DateTimeModified] [datetime2](7) NOT NULL,
		[Activity] [nvarchar](20) NOT NULL,
		[ErrorID] [int] NOT NULL,
		[DateTimeLogged] [datetime2](7) NOT NULL,
		[GeneratedByUser] [int] NULL,
		[ErrorstringID] [int] NOT NULL,
		[ExceptionCode] [int] NOT NULL,
		[GeneratedByIPAddress] nvarchar(45) NULL,
	 CONSTRAINT [PK_Errors] PRIMARY KEY CLUSTERED 
	(
		[HistoryErrorID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]


	PRINT 'Creating table [hist].[Errorstrings]...'

	CREATE TABLE [hist].[Errorstrings](
		[HistoryErrorstringID] [int] IDENTITY(1,1) NOT NULL,
		[HostName] [nvarchar](128) NOT NULL,
		[HostID] [nvarchar](10) NOT NULL,
		[ClientNetAddress] [nvarchar](48) NOT NULL,
		[DateTimeModified] [datetime2](7) NOT NULL,
		[Activity] [nvarchar](20) NOT NULL,
		[ErrorstringID] [int] NOT NULL,
		[Errorstring] [nvarchar](4000) NOT NULL,
		[DateTimeFirstLogged] [datetime2](7) NOT NULL,
	 CONSTRAINT [PK_Errorstrings] PRIMARY KEY CLUSTERED 
	(
		[HistoryErrorstringID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]


	PRINT 'Creating table [hist].[Loaners]...'

	CREATE TABLE [hist].[Loaners](
		[HistoryLoanerID] [int] IDENTITY(1,1) NOT NULL,
		[HostName] [nvarchar](128) NOT NULL,
		[HostID] [nvarchar](10) NOT NULL,
		[ClientNetAddress] [nvarchar](48) NOT NULL,
		[DateTimeModified] [datetime2](7) NOT NULL,
		[Activity] [nvarchar](20) NOT NULL,
		[LoanerID] [int] NOT NULL,
		[Description] [nvarchar](100) NOT NULL,
	 CONSTRAINT [PK_Loaners] PRIMARY KEY CLUSTERED 
	(
		[HistoryLoanerID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]


	PRINT 'Creating table [hist].[Logins]...'

	CREATE TABLE [hist].[Logins](
		[HistoryLoginID] [int] IDENTITY(1,1) NOT NULL,
		[HostName] [nvarchar](128) NOT NULL,
		[HostID] [nvarchar](10) NOT NULL,
		[ClientNetAddress] [nvarchar](48) NOT NULL,
		[DateTimeModified] [datetime2](7) NOT NULL,
		[Activity] [nvarchar](20) NOT NULL,
		[LoginID] [int] NOT NULL,
		[UserID] [int] NOT NULL,
		[DateTimeLogin] [datetime2](7) NOT NULL,
		[DateTimeLastActivity] [datetime2](7) NOT NULL,
	 CONSTRAINT [PK_Logins] PRIMARY KEY CLUSTERED 
	(
		[HistoryLoginID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]


	PRINT 'Creating table [hist].[Missingmovies]...'

	CREATE TABLE [hist].[Missingmovies](
		[HistoryMissingmovieID] [int] IDENTITY(1,1) NOT NULL,
		[HostName] [nvarchar](128) NOT NULL,
		[HostID] [nvarchar](10) NOT NULL,
		[ClientNetAddress] [nvarchar](48) NOT NULL,
		[DateTimeModified] [datetime2](7) NOT NULL,
		[Activity] [nvarchar](20) NOT NULL,
		[MissingmovieID] [int] NOT NULL,
		[MovieID] [int] NOT NULL,
		[RegisteredByUser] [int] NOT NULL,
		[DateTimeMissing] [datetime2](7) NOT NULL,
		[DateTimeReturn] [datetime2](7) NULL,
	 CONSTRAINT [PK_Missingmovies] PRIMARY KEY CLUSTERED 
	(
		[HistoryMissingmovieID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]

	
	PRINT 'Creating table [hist].[Movieloans]...'

	CREATE TABLE [hist].[Movieloans](
		[HistoryMovieloanID] [int] IDENTITY(1,1) NOT NULL,
		[HostName] [nvarchar](128) NOT NULL,
		[HostID] [nvarchar](10) NOT NULL,
		[ClientNetAddress] [nvarchar](48) NOT NULL,
		[DateTimeModified] [datetime2](7) NOT NULL,
		[Activity] [nvarchar](20) NOT NULL,
		[MovieloanID] [int] NOT NULL,
		[MovieID] [int] NOT NULL,
		[LoanerID] [int] NOT NULL,
		[RegisteredByUser] [int] NOT NULL,
		[DateTimeLoan] [datetime2](7) NOT NULL,
		[DateTimeReturn] [datetime2](7) NULL,
	 CONSTRAINT [PK_Movieloans] PRIMARY KEY CLUSTERED 
	(
		[HistoryMovieloanID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]


	PRINT 'Creating table [hist].[Moviequalities]...'

	CREATE TABLE [hist].[Moviequalities](
		[HistoryMoviequalityID] [int] IDENTITY(1,1) NOT NULL,
		[HostName] [nvarchar](128) NOT NULL,
		[HostID] [nvarchar](10) NOT NULL,
		[ClientNetAddress] [nvarchar](48) NOT NULL,
		[DateTimeModified] [datetime2](7) NOT NULL,
		[Activity] [nvarchar](20) NOT NULL,
		[MoviequalityID] [int] NOT NULL,
		[Description] [nvarchar](100) NOT NULL,
	 CONSTRAINT [PK_Moviequality] PRIMARY KEY CLUSTERED 
	(
		[HistoryMoviequalityID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]

*/

	PRINT 'Creating table [hist].[Movies]...'

	CREATE TABLE [hist].[Movies](
		[HistoryMovieID] [int] IDENTITY(1,1) NOT NULL,
		[HostName] [nvarchar](128) NOT NULL,
		[HostID] [nvarchar](10) NOT NULL,
		[ClientNetAddress] [nvarchar](48) NOT NULL,
		[DateTimeModified] [datetime2](7) NOT NULL,
		[ModifiedByUser] [int] NOT NULL,
		[Activity] [nvarchar](20) NOT NULL,
		[MovieID] [int] NOT NULL,
		[DateTimeCreated] [datetime2](7) NOT NULL,
		[CreatedByUser] [int] NOT NULL,
		[DateTimeDeleted] [datetime2](7) NULL,
		[DeletedByUser] [int] NULL,
		[Title] [nvarchar](100) NOT NULL,
		[IMDBID] [nvarchar](50) NULL,
		[IMDBRating] [float] NULL,
		[RunningTime] [int] NULL,
		[IMDBVotes] [int] NULL,
		[PlotSummary] [nvarchar](max) NULL,
		[Plot] [nvarchar](max) NULL,
		[ReleasedYear] [int] NULL,
		[Language] [nvarchar](1000) NULL,
		[Country] [nvarchar](1000) NULL,
		[Genre] [nvarchar](1000) NULL,
		[Director] [nvarchar](1000) NULL,
		[Cast] [nvarchar](1000) NULL,
		[PosterURL] [nvarchar](1000) NULL,
		[MoviestatusID] [int] NOT NULL,
		[MoviequalityID] [int] NOT NULL,
	 CONSTRAINT [PK_Movies] PRIMARY KEY CLUSTERED 
	(
		[HistoryMovieID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

/*

	PRINT 'Creating table [hist].[Moviesortings]...'

	CREATE TABLE [hist].[Moviesortings](
		[HistoryMoviesortingID] [int] IDENTITY(1,1) NOT NULL,
		[HostName] [nvarchar](128) NOT NULL,
		[HostID] [nvarchar](10) NOT NULL,
		[ClientNetAddress] [nvarchar](48) NOT NULL,
		[DateTimeModified] [datetime2](7) NOT NULL,
		[Activity] [nvarchar](20) NOT NULL,
		[MoviesortingID] [int] NOT NULL,
		[Description] [nvarchar](50) NOT NULL,
		[SortType] [nvarchar](50) NULL,
	 CONSTRAINT [PK_Moviesorting] PRIMARY KEY CLUSTERED 
	(
		[HistoryMoviesortingID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]


	PRINT 'Creating table [hist].[Moviestatuses]...'

	CREATE TABLE [hist].[Moviestatuses](
		[HistoryMoviestatusID] [int] IDENTITY(1,1) NOT NULL,
		[HostName] [nvarchar](128) NOT NULL,
		[HostID] [nvarchar](10) NOT NULL,
		[ClientNetAddress] [nvarchar](48) NOT NULL,
		[DateTimeModified] [datetime2](7) NOT NULL,
		[Activity] [nvarchar](20) NOT NULL,
		[MoviestatusID] [int] NOT NULL,
		[Description] [nvarchar](100) NOT NULL,
	 CONSTRAINT [PK_Moviestatus] PRIMARY KEY CLUSTERED 
	(
		[HistoryMoviestatusID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]


	PRINT 'Creating table [hist].[Pages_UserRoles]...'

	CREATE TABLE [hist].[Pages_UserRoles](
		[HistoryPages_UserRolesID] [int] IDENTITY(1,1) NOT NULL,
		[HostName] [nvarchar](128) NOT NULL,
		[HostID] [nvarchar](10) NOT NULL,
		[ClientNetAddress] [nvarchar](48) NOT NULL,
		[DateTimeModified] [datetime2](7) NOT NULL,
		[Activity] [nvarchar](20) NOT NULL,
		[Pages_UserRolesID] [int] NOT NULL,
		[PageID] [int] NOT NULL,
		[UserRoleID] [int] NOT NULL,
	 CONSTRAINT [PK_Pages_UserRoles] PRIMARY KEY CLUSTERED 
	(
		[HistoryPages_UserRolesID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]

*/

	PRINT 'Creating table [hist].[Posters]...'

	CREATE TABLE [hist].[Posters](
		[HistoryPosterID] [int] IDENTITY(1,1) NOT NULL,
		[HostName] [nvarchar](128) NOT NULL,
		[HostID] [nvarchar](10) NOT NULL,
		[ClientNetAddress] [nvarchar](48) NOT NULL,
		[DateTimeModified] [datetime2](7) NOT NULL,
		[Activity] [nvarchar](20) NOT NULL,
		[PosterID] [int] NOT NULL,
		[DateTimeCreated] [datetime2](7) NOT NULL,
		[CreatedByUser] [int] NOT NULL,
		[MovieID] [int] NOT NULL,
		[Filename] [nvarchar](100) NOT NULL,
		[Type] [nvarchar](50) NOT NULL,
		[Size] [int] NOT NULL,
		[MouseoverTitle] [nvarchar](100) NOT NULL,
	 CONSTRAINT [PK_Posters] PRIMARY KEY CLUSTERED 
	(
		[HistoryPosterID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]

/*

	PRINT 'Creating table [hist].[Subjects_UserRoles]...'

	CREATE TABLE [hist].[Subjects_UserRoles](
		[HistorySubjects_UserRolesID] [int] IDENTITY(1,1) NOT NULL,
		[HostName] [nvarchar](128) NOT NULL,
		[HostID] [nvarchar](10) NOT NULL,
		[ClientNetAddress] [nvarchar](48) NOT NULL,
		[DateTimeModified] [datetime2](7) NOT NULL,
		[Activity] [nvarchar](20) NOT NULL,
		[Subjects_UserRolesID] [int] NOT NULL,
		[SubjectID] [int] NOT NULL,
		[UserRoleID] [int] NOT NULL,
	 CONSTRAINT [PK_Subjects_UserRoles] PRIMARY KEY CLUSTERED 
	(
		[HistorySubjects_UserRolesID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]

*/

	PRINT 'Creating table [hist].[Users]...'

	CREATE TABLE [hist].[Users](
		[HistoryUserID] [int] IDENTITY(1,1) NOT NULL,
		[HostName] [nvarchar](128) NOT NULL,
		[HostID] [nvarchar](10) NOT NULL,
		[ClientNetAddress] [nvarchar](48) NOT NULL,
		[DateTimeModified] [datetime2](7) NOT NULL,
		[Activity] [nvarchar](20) NOT NULL,
		[UserID] [int] NOT NULL,
		[Username] [nvarchar](50) NOT NULL,
		[HashedPassword] [nvarchar](60) NOT NULL,
		[UserRoleID] [int] NOT NULL,
		[DateTimeCreated] [datetime2](7) NOT NULL,
	 CONSTRAINT [PK_Users] PRIMARY KEY CLUSTERED
	(
		[HistoryUserID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]

/*
	
	PRINT 'Creating table [hist].[Web_Pages]...'

	CREATE TABLE [hist].[Web_Pages](
		[HistoryPageID] [int] IDENTITY(1,1) NOT NULL,
		[HostName] [nvarchar](128) NOT NULL,
		[HostID] [nvarchar](10) NOT NULL,
		[ClientNetAddress] [nvarchar](48) NOT NULL,
		[DateTimeModified] [datetime2](7) NOT NULL,
		[Activity] [nvarchar](20) NOT NULL,
		[PageID] [int] NOT NULL,
		[SubjectID] [int] NOT NULL,
		[MenuName] [nvarchar](50) NOT NULL,
		[Position] [int] NULL,
		[Visible] [bit] NULL,
		[Contents] [nvarchar](1000) NULL,
		[Admin] [bit] NOT NULL,
	 CONSTRAINT [PK_Web_Pages] PRIMARY KEY CLUSTERED 
	(
		[HistoryPageID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]


	PRINT 'Creating table [hist].[Web_Subjects]...'

	CREATE TABLE [hist].[Web_Subjects](
		[HistorySubjectID] [int] IDENTITY(1,1) NOT NULL,
		[HostName] [nvarchar](128) NOT NULL,
		[HostID] [nvarchar](10) NOT NULL,
		[ClientNetAddress] [nvarchar](48) NOT NULL,
		[DateTimeModified] [datetime2](7) NOT NULL,
		[Activity] [nvarchar](20) NOT NULL,
		[SubjectID] [int] NOT NULL,
		[MenuName] [nvarchar](50) NOT NULL,
		[Position] [int] NULL,
		[Visible] [bit] NULL,
		[Admin] [bit] NOT NULL,
	 CONSTRAINT [PK_Web_Subjects] PRIMARY KEY CLUSTERED 
	(
		[HistorySubjectID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]


	PRINT 'Creating table [hist].[Web_UserRoles]...'

	CREATE TABLE [hist].[Web_UserRoles](
		[HistoryUserRoleID] [int] IDENTITY(1,1) NOT NULL,
		[HostName] [nvarchar](128) NOT NULL,
		[HostID] [nvarchar](10) NOT NULL,
		[ClientNetAddress] [nvarchar](48) NOT NULL,
		[DateTimeModified] [datetime2](7) NOT NULL,
		[Activity] [nvarchar](20) NOT NULL,
		[UserRoleID] [int] NOT NULL,
		[UserRoleName] [nvarchar](50) NOT NULL,
	 CONSTRAINT [PK_UserRoles] PRIMARY KEY CLUSTERED 
	(
		[HistoryUserRoleID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]


*/


	---the above is checked

		
	
	
	
/*	
	
	PRINT('Creating audit triggers for the table [dbo].[Errors]...');

	EXEC('
		CREATE TRIGGER [dbo].[trg_audit_errors] 
			ON  [dbo].[Errors]
			AFTER INSERT, DELETE, UPDATE
		AS
		DECLARE @Activity [nvarchar](20);
		DECLARE @ModifiedByClientNetAddress [nvarchar](48) = CAST(CONNECTIONPROPERTY(''client_net_address'') AS nvarchar(48));
		DECLARE @ModifiedByHostName [nvarchar](128) = HOST_NAME();
		DECLARE @ModifiedByHostID [nvarchar](10) = HOST_ID(); 
		DECLARE @DateTimeStamp [datetime2](7) = SYSDATETIME();
		
		BEGIN
			-- SET NOCOUNT ON added to prevent extra result sets from
			-- interfering with SELECT statements.
			SET NOCOUNT ON;
			
			IF EXISTS(SELECT * from Inserted) AND NOT EXISTS(SELECT * from Deleted)
			--insert
			BEGIN
				SET @Activity = ''INSERT'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--update
			BEGIN
				SET @activity = ''UPDATE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF NOT EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--delete
			BEGIN
				SET @activity = ''DELETE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Deleted
			END
	
		END
	');

	
	PRINT('Creating audit triggers for the table [dbo].[Errorstrings]...');

	EXEC('
		CREATE TRIGGER [dbo].[trg_audit_errorstrings] 
			ON  [dbo].[Errorstrings]
			AFTER INSERT, DELETE, UPDATE
		AS
		DECLARE @Activity [nvarchar](20);
		DECLARE @ModifiedByClientNetAddress [nvarchar](48) = CAST(CONNECTIONPROPERTY(''client_net_address'') AS nvarchar(48));
		DECLARE @ModifiedByHostName [nvarchar](128) = HOST_NAME();
		DECLARE @ModifiedByHostID [nvarchar](10) = HOST_ID(); 
		DECLARE @DateTimeStamp [datetime2](7) = SYSDATETIME();
		
		BEGIN
			-- SET NOCOUNT ON added to prevent extra result sets from
			-- interfering with SELECT statements.
			SET NOCOUNT ON;
			
			IF EXISTS(SELECT * from Inserted) AND NOT EXISTS(SELECT * from Deleted)
			--insert
			BEGIN
				SET @Activity = ''INSERT'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errorstrings](ErrorstringID, Errorstring, DateTimeFirstLogged, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorstringID, Errorstring, DateTimeFirstLogged, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--update
			BEGIN
				SET @activity = ''UPDATE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errorstrings](ErrorstringID, Errorstring, DateTimeFirstLogged, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorstringID, Errorstring, DateTimeFirstLogged, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF NOT EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--delete
			BEGIN
				SET @activity = ''DELETE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errorstrings](ErrorstringID, Errorstring, DateTimeFirstLogged, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorstringID, Errorstring, DateTimeFirstLogged, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Deleted
			END
	
		END
	');

	[dbo].[Loaners]
	PRINT('Creating audit triggers for the table [dbo].[Loaners]...');

	EXEC('
		CREATE TRIGGER [dbo].[trg_audit_errors] 
			ON  [dbo].[Errors]
			AFTER INSERT, DELETE, UPDATE
		AS
		DECLARE @Activity [nvarchar](20);
		DECLARE @ModifiedByClientNetAddress [nvarchar](48) = CAST(CONNECTIONPROPERTY(''client_net_address'') AS nvarchar(48));
		DECLARE @ModifiedByHostName [nvarchar](128) = HOST_NAME();
		DECLARE @ModifiedByHostID [nvarchar](10) = HOST_ID(); 
		DECLARE @DateTimeStamp [datetime2](7) = SYSDATETIME();
		
		BEGIN
			-- SET NOCOUNT ON added to prevent extra result sets from
			-- interfering with SELECT statements.
			SET NOCOUNT ON;
			
			IF EXISTS(SELECT * from Inserted) AND NOT EXISTS(SELECT * from Deleted)
			--insert
			BEGIN
				SET @Activity = ''INSERT'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--update
			BEGIN
				SET @activity = ''UPDATE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF NOT EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--delete
			BEGIN
				SET @activity = ''DELETE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Deleted
			END
	
		END
	');	
	
	
	[dbo].[Logins]
	PRINT('Creating audit triggers for the table [dbo].[Logins]...');

	EXEC('
		CREATE TRIGGER [dbo].[trg_audit_errors] 
			ON  [dbo].[Errors]
			AFTER INSERT, DELETE, UPDATE
		AS
		DECLARE @Activity [nvarchar](20);
		DECLARE @ModifiedByClientNetAddress [nvarchar](48) = CAST(CONNECTIONPROPERTY(''client_net_address'') AS nvarchar(48));
		DECLARE @ModifiedByHostName [nvarchar](128) = HOST_NAME();
		DECLARE @ModifiedByHostID [nvarchar](10) = HOST_ID(); 
		DECLARE @DateTimeStamp [datetime2](7) = SYSDATETIME();
		
		BEGIN
			-- SET NOCOUNT ON added to prevent extra result sets from
			-- interfering with SELECT statements.
			SET NOCOUNT ON;
			
			IF EXISTS(SELECT * from Inserted) AND NOT EXISTS(SELECT * from Deleted)
			--insert
			BEGIN
				SET @Activity = ''INSERT'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--update
			BEGIN
				SET @activity = ''UPDATE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF NOT EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--delete
			BEGIN
				SET @activity = ''DELETE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Deleted
			END
	
		END
	');	
	
	
	[dbo].[Missingmovies]
	PRINT('Creating audit triggers for the table [dbo].[Missingmovies]...');

	EXEC('
		CREATE TRIGGER [dbo].[trg_audit_errors] 
			ON  [dbo].[Errors]
			AFTER INSERT, DELETE, UPDATE
		AS
		DECLARE @Activity [nvarchar](20);
		DECLARE @ModifiedByClientNetAddress [nvarchar](48) = CAST(CONNECTIONPROPERTY(''client_net_address'') AS nvarchar(48));
		DECLARE @ModifiedByHostName [nvarchar](128) = HOST_NAME();
		DECLARE @ModifiedByHostID [nvarchar](10) = HOST_ID(); 
		DECLARE @DateTimeStamp [datetime2](7) = SYSDATETIME();
		
		BEGIN
			-- SET NOCOUNT ON added to prevent extra result sets from
			-- interfering with SELECT statements.
			SET NOCOUNT ON;
			
			IF EXISTS(SELECT * from Inserted) AND NOT EXISTS(SELECT * from Deleted)
			--insert
			BEGIN
				SET @Activity = ''INSERT'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--update
			BEGIN
				SET @activity = ''UPDATE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF NOT EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--delete
			BEGIN
				SET @activity = ''DELETE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Deleted
			END
	
		END
	');
	
	
	[dbo].[Movieloans]
	PRINT('Creating audit triggers for the table [dbo].[Movieloans]...');

	EXEC('
		CREATE TRIGGER [dbo].[trg_audit_errors] 
			ON  [dbo].[Errors]
			AFTER INSERT, DELETE, UPDATE
		AS
		DECLARE @Activity [nvarchar](20);
		DECLARE @ModifiedByClientNetAddress [nvarchar](48) = CAST(CONNECTIONPROPERTY(''client_net_address'') AS nvarchar(48));
		DECLARE @ModifiedByHostName [nvarchar](128) = HOST_NAME();
		DECLARE @ModifiedByHostID [nvarchar](10) = HOST_ID(); 
		DECLARE @DateTimeStamp [datetime2](7) = SYSDATETIME();
		
		BEGIN
			-- SET NOCOUNT ON added to prevent extra result sets from
			-- interfering with SELECT statements.
			SET NOCOUNT ON;
			
			IF EXISTS(SELECT * from Inserted) AND NOT EXISTS(SELECT * from Deleted)
			--insert
			BEGIN
				SET @Activity = ''INSERT'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--update
			BEGIN
				SET @activity = ''UPDATE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF NOT EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--delete
			BEGIN
				SET @activity = ''DELETE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Deleted
			END
	
		END
	');	
	
	
	[dbo].[Moviequalities]
	PRINT('Creating audit triggers for the table [dbo].[Moviequalities]...');

	EXEC('
		CREATE TRIGGER [dbo].[trg_audit_errors] 
			ON  [dbo].[Errors]
			AFTER INSERT, DELETE, UPDATE
		AS
		DECLARE @Activity [nvarchar](20);
		DECLARE @ModifiedByClientNetAddress [nvarchar](48) = CAST(CONNECTIONPROPERTY(''client_net_address'') AS nvarchar(48));
		DECLARE @ModifiedByHostName [nvarchar](128) = HOST_NAME();
		DECLARE @ModifiedByHostID [nvarchar](10) = HOST_ID(); 
		DECLARE @DateTimeStamp [datetime2](7) = SYSDATETIME();
		
		BEGIN
			-- SET NOCOUNT ON added to prevent extra result sets from
			-- interfering with SELECT statements.
			SET NOCOUNT ON;
			
			IF EXISTS(SELECT * from Inserted) AND NOT EXISTS(SELECT * from Deleted)
			--insert
			BEGIN
				SET @Activity = ''INSERT'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--update
			BEGIN
				SET @activity = ''UPDATE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF NOT EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--delete
			BEGIN
				SET @activity = ''DELETE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Deleted
			END
	
		END
	');	
	
	
	[dbo].[Moviesortings]
	PRINT('Creating audit triggers for the table [dbo].[Moviesortings]...');

	EXEC('
		CREATE TRIGGER [dbo].[trg_audit_errors] 
			ON  [dbo].[Errors]
			AFTER INSERT, DELETE, UPDATE
		AS
		DECLARE @Activity [nvarchar](20);
		DECLARE @ModifiedByClientNetAddress [nvarchar](48) = CAST(CONNECTIONPROPERTY(''client_net_address'') AS nvarchar(48));
		DECLARE @ModifiedByHostName [nvarchar](128) = HOST_NAME();
		DECLARE @ModifiedByHostID [nvarchar](10) = HOST_ID(); 
		DECLARE @DateTimeStamp [datetime2](7) = SYSDATETIME();
		
		BEGIN
			-- SET NOCOUNT ON added to prevent extra result sets from
			-- interfering with SELECT statements.
			SET NOCOUNT ON;
			
			IF EXISTS(SELECT * from Inserted) AND NOT EXISTS(SELECT * from Deleted)
			--insert
			BEGIN
				SET @Activity = ''INSERT'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--update
			BEGIN
				SET @activity = ''UPDATE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF NOT EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--delete
			BEGIN
				SET @activity = ''DELETE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Deleted
			END
	
		END
	');	


	[dbo].[Moviestatuses]
	PRINT('Creating audit triggers for the table [dbo].[Moviestatuses]...');

	EXEC('
		CREATE TRIGGER [dbo].[trg_audit_errors] 
			ON  [dbo].[Errors]
			AFTER INSERT, DELETE, UPDATE
		AS
		DECLARE @Activity [nvarchar](20);
		DECLARE @ModifiedByClientNetAddress [nvarchar](48) = CAST(CONNECTIONPROPERTY(''client_net_address'') AS nvarchar(48));
		DECLARE @ModifiedByHostName [nvarchar](128) = HOST_NAME();
		DECLARE @ModifiedByHostID [nvarchar](10) = HOST_ID(); 
		DECLARE @DateTimeStamp [datetime2](7) = SYSDATETIME();
		
		BEGIN
			-- SET NOCOUNT ON added to prevent extra result sets from
			-- interfering with SELECT statements.
			SET NOCOUNT ON;
			
			IF EXISTS(SELECT * from Inserted) AND NOT EXISTS(SELECT * from Deleted)
			--insert
			BEGIN
				SET @Activity = ''INSERT'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--update
			BEGIN
				SET @activity = ''UPDATE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF NOT EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--delete
			BEGIN
				SET @activity = ''DELETE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Deleted
			END
	
		END
	');


	[dbo].[Pages_UserRoles]
	PRINT('Creating audit triggers for the table [dbo].[Pages_UserRoles]...');

	EXEC('
		CREATE TRIGGER [dbo].[trg_audit_errors] 
			ON  [dbo].[Errors]
			AFTER INSERT, DELETE, UPDATE
		AS
		DECLARE @Activity [nvarchar](20);
		DECLARE @ModifiedByClientNetAddress [nvarchar](48) = CAST(CONNECTIONPROPERTY(''client_net_address'') AS nvarchar(48));
		DECLARE @ModifiedByHostName [nvarchar](128) = HOST_NAME();
		DECLARE @ModifiedByHostID [nvarchar](10) = HOST_ID(); 
		DECLARE @DateTimeStamp [datetime2](7) = SYSDATETIME();
		
		BEGIN
			-- SET NOCOUNT ON added to prevent extra result sets from
			-- interfering with SELECT statements.
			SET NOCOUNT ON;
			
			IF EXISTS(SELECT * from Inserted) AND NOT EXISTS(SELECT * from Deleted)
			--insert
			BEGIN
				SET @Activity = ''INSERT'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--update
			BEGIN
				SET @activity = ''UPDATE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF NOT EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--delete
			BEGIN
				SET @activity = ''DELETE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Deleted
			END
	
		END
	');
	
*/	

	PRINT('Creating audit triggers for the table [dbo].[Posters]...');

	EXEC('
		CREATE TRIGGER [dbo].[trg_audit_posters] 
			ON  [dbo].[Posters]
			AFTER INSERT, DELETE, UPDATE
		AS
		DECLARE @Activity [nvarchar](20);
		DECLARE @ModifiedByClientNetAddress [nvarchar](48) = CAST(CONNECTIONPROPERTY(''client_net_address'') AS nvarchar(48));
		DECLARE @ModifiedByHostName [nvarchar](128) = HOST_NAME();
		DECLARE @ModifiedByHostID [nvarchar](10) = HOST_ID(); 
		DECLARE @DateTimeStamp [datetime2](7) = SYSDATETIME();
		
		BEGIN
			-- SET NOCOUNT ON added to prevent extra result sets from
			-- interfering with SELECT statements.
			SET NOCOUNT ON;
			
			IF EXISTS(SELECT * from Inserted) AND NOT EXISTS(SELECT * from Deleted)
			--insert
			BEGIN
				SET @Activity = ''INSERT'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Posters](PosterID, DateTimeCreated, CreatedByUser, MovieID, Filename, Type, Size, MouseoverTitle, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT PosterID, DateTimeCreated, CreatedByUser, MovieID, Filename, Type, Size, MouseoverTitle, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--update
			BEGIN
				SET @activity = ''UPDATE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Posters](PosterID, DateTimeCreated, CreatedByUser, MovieID, Filename, Type, Size, MouseoverTitle, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT PosterID, DateTimeCreated, CreatedByUser, MovieID, Filename, Type, Size, MouseoverTitle, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF NOT EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--delete
			BEGIN
				SET @activity = ''DELETE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Posters](PosterID, DateTimeCreated, CreatedByUser, MovieID, Filename, Type, Size, MouseoverTitle, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT PosterID, DateTimeCreated, CreatedByUser, MovieID, Filename, Type, Size, MouseoverTitle, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Deleted
			END
	
		END
	');

/*
	[dbo].[SchemaChanges]
	PRINT('Creating audit triggers for the table [dbo].[SchemaChanges]...');

	EXEC('
		CREATE TRIGGER [dbo].[trg_audit_errors] 
			ON  [dbo].[Errors]
			AFTER INSERT, DELETE, UPDATE
		AS
		DECLARE @Activity [nvarchar](20);
		DECLARE @ModifiedByClientNetAddress [nvarchar](48) = CAST(CONNECTIONPROPERTY(''client_net_address'') AS nvarchar(48));
		DECLARE @ModifiedByHostName [nvarchar](128) = HOST_NAME();
		DECLARE @ModifiedByHostID [nvarchar](10) = HOST_ID(); 
		DECLARE @DateTimeStamp [datetime2](7) = SYSDATETIME();
		
		BEGIN
			-- SET NOCOUNT ON added to prevent extra result sets from
			-- interfering with SELECT statements.
			SET NOCOUNT ON;
			
			IF EXISTS(SELECT * from Inserted) AND NOT EXISTS(SELECT * from Deleted)
			--insert
			BEGIN
				SET @Activity = ''INSERT'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--update
			BEGIN
				SET @activity = ''UPDATE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF NOT EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--delete
			BEGIN
				SET @activity = ''DELETE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Deleted
			END
	
		END
	');


	[dbo].[Subjects_UserRoles]
	PRINT('Creating audit triggers for the table [dbo].[Subjects_UserRoles]...');

	EXEC('
		CREATE TRIGGER [dbo].[trg_audit_errors] 
			ON  [dbo].[Errors]
			AFTER INSERT, DELETE, UPDATE
		AS
		DECLARE @Activity [nvarchar](20);
		DECLARE @ModifiedByClientNetAddress [nvarchar](48) = CAST(CONNECTIONPROPERTY(''client_net_address'') AS nvarchar(48));
		DECLARE @ModifiedByHostName [nvarchar](128) = HOST_NAME();
		DECLARE @ModifiedByHostID [nvarchar](10) = HOST_ID(); 
		DECLARE @DateTimeStamp [datetime2](7) = SYSDATETIME();
		
		BEGIN
			-- SET NOCOUNT ON added to prevent extra result sets from
			-- interfering with SELECT statements.
			SET NOCOUNT ON;
			
			IF EXISTS(SELECT * from Inserted) AND NOT EXISTS(SELECT * from Deleted)
			--insert
			BEGIN
				SET @Activity = ''INSERT'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--update
			BEGIN
				SET @activity = ''UPDATE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF NOT EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--delete
			BEGIN
				SET @activity = ''DELETE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Deleted
			END
	
		END
	');

*/

	PRINT('Creating audit triggers for the table [dbo].[Users]...');

	EXEC('
		CREATE TRIGGER [dbo].[trg_audit_users] 
			ON  [dbo].[Users]
			AFTER INSERT, DELETE, UPDATE
		AS
		DECLARE @Activity [nvarchar](20);
		DECLARE @ModifiedByClientNetAddress [nvarchar](48) = CAST(CONNECTIONPROPERTY(''client_net_address'') AS nvarchar(48));
		DECLARE @ModifiedByHostName [nvarchar](128) = HOST_NAME();
		DECLARE @ModifiedByHostID [nvarchar](10) = HOST_ID(); 
		DECLARE @DateTimeStamp [datetime2](7) = SYSDATETIME();
		
		BEGIN
			-- SET NOCOUNT ON added to prevent extra result sets from
			-- interfering with SELECT statements.
			SET NOCOUNT ON;
			
			IF EXISTS(SELECT * from Inserted) AND NOT EXISTS(SELECT * from Deleted)
			--insert
			BEGIN
				SET @Activity = ''INSERT'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Users](UserID, Username, HashedPassword, UserRoleID, DateTimeCreated, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT UserID, Username, HashedPassword, UserRoleID, DateTimeCreated, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--update
			BEGIN
				SET @activity = ''UPDATE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Users](UserID, Username, HashedPassword, UserRoleID, DateTimeCreated, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT UserID, Username, HashedPassword, UserRoleID, DateTimeCreated, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF NOT EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--delete
			BEGIN
				SET @activity = ''DELETE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Users](UserID, Username, HashedPassword, UserRoleID, DateTimeCreated, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT UserID, Username, HashedPassword, UserRoleID, DateTimeCreated, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Deleted
			END
	
		END
	');

/*
	[dbo].[Web_Pages]
	PRINT('Creating audit triggers for the table [dbo].[Web_Pages]...');

	EXEC('
		CREATE TRIGGER [dbo].[trg_audit_errors] 
			ON  [dbo].[Errors]
			AFTER INSERT, DELETE, UPDATE
		AS
		DECLARE @Activity [nvarchar](20);
		DECLARE @ModifiedByClientNetAddress [nvarchar](48) = CAST(CONNECTIONPROPERTY(''client_net_address'') AS nvarchar(48));
		DECLARE @ModifiedByHostName [nvarchar](128) = HOST_NAME();
		DECLARE @ModifiedByHostID [nvarchar](10) = HOST_ID(); 
		DECLARE @DateTimeStamp [datetime2](7) = SYSDATETIME();
		
		BEGIN
			-- SET NOCOUNT ON added to prevent extra result sets from
			-- interfering with SELECT statements.
			SET NOCOUNT ON;
			
			IF EXISTS(SELECT * from Inserted) AND NOT EXISTS(SELECT * from Deleted)
			--insert
			BEGIN
				SET @Activity = ''INSERT'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--update
			BEGIN
				SET @activity = ''UPDATE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF NOT EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--delete
			BEGIN
				SET @activity = ''DELETE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Deleted
			END
	
		END
	');


	[dbo].[Web_Subjects]
	PRINT('Creating audit triggers for the table [dbo].[Web_Subjects]...');

	EXEC('
		CREATE TRIGGER [dbo].[trg_audit_errors] 
			ON  [dbo].[Errors]
			AFTER INSERT, DELETE, UPDATE
		AS
		DECLARE @Activity [nvarchar](20);
		DECLARE @ModifiedByClientNetAddress [nvarchar](48) = CAST(CONNECTIONPROPERTY(''client_net_address'') AS nvarchar(48));
		DECLARE @ModifiedByHostName [nvarchar](128) = HOST_NAME();
		DECLARE @ModifiedByHostID [nvarchar](10) = HOST_ID(); 
		DECLARE @DateTimeStamp [datetime2](7) = SYSDATETIME();
		
		BEGIN
			-- SET NOCOUNT ON added to prevent extra result sets from
			-- interfering with SELECT statements.
			SET NOCOUNT ON;
			
			IF EXISTS(SELECT * from Inserted) AND NOT EXISTS(SELECT * from Deleted)
			--insert
			BEGIN
				SET @Activity = ''INSERT'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--update
			BEGIN
				SET @activity = ''UPDATE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF NOT EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--delete
			BEGIN
				SET @activity = ''DELETE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Deleted
			END
	
		END
	');


	[dbo].[Web_UserRoles]
	PRINT('Creating audit triggers for the table [dbo].[Web_UserRoles]...');

	EXEC('
		CREATE TRIGGER [dbo].[trg_audit_errors] 
			ON  [dbo].[Errors]
			AFTER INSERT, DELETE, UPDATE
		AS
		DECLARE @Activity [nvarchar](20);
		DECLARE @ModifiedByClientNetAddress [nvarchar](48) = CAST(CONNECTIONPROPERTY(''client_net_address'') AS nvarchar(48));
		DECLARE @ModifiedByHostName [nvarchar](128) = HOST_NAME();
		DECLARE @ModifiedByHostID [nvarchar](10) = HOST_ID(); 
		DECLARE @DateTimeStamp [datetime2](7) = SYSDATETIME();
		
		BEGIN
			-- SET NOCOUNT ON added to prevent extra result sets from
			-- interfering with SELECT statements.
			SET NOCOUNT ON;
			
			IF EXISTS(SELECT * from Inserted) AND NOT EXISTS(SELECT * from Deleted)
			--insert
			BEGIN
				SET @Activity = ''INSERT'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--update
			BEGIN
				SET @activity = ''UPDATE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Inserted
			END

			IF NOT EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--delete
			BEGIN
				SET @activity = ''DELETE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Errors](ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, Activity, HostName, HostID, ClientNetAddress, DateTimeModified)
				SELECT ErrorID, DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress, @Activity, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @DateTimeStamp FROM Deleted
			END
	
		END
	');


*/


/*
	Errorstrings
	Loaners
	Logins
	Missingmovies
	Movieloans
	Moviequalities
	Moviesortings
	Moviestatuses
	Pages_UserRoles
	Posters
	SchemaChanges
	Subjects_UserRoles
	Users
	Web_Pages
	Web_Subjects
	Web_UserRoles
*/


	PRINT('Creating audit triggers for the table [dbo].[Movies]...');

	EXEC('
		CREATE TRIGGER [dbo].[trg_audit_movies] 
			ON  [dbo].[Movies]
			AFTER INSERT, DELETE, UPDATE
		AS
		DECLARE @Activity nvarchar(20);
		DECLARE @ModifiedByClientNetAddress [nvarchar](48) = CAST(CONNECTIONPROPERTY(''client_net_address'') AS nvarchar(48));
		DECLARE @ModifiedByHostName [nvarchar](128) = HOST_NAME();
		DECLARE @ModifiedByHostID [nvarchar](10) = HOST_ID(); 

		BEGIN
			-- SET NOCOUNT ON added to prevent extra result sets from
			-- interfering with SELECT statements.
			SET NOCOUNT ON;
			
			IF EXISTS(SELECT * from Inserted) AND NOT EXISTS(SELECT * from Deleted)
			--insert
			BEGIN
				SET @Activity = ''INSERT'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Movies](DateTimeModified, ModifiedByUser, MovieID, DateTimeCreated, CreatedByUser, DateTimeDeleted, DeletedByUser, Title, IMDBID, IMDBRating, RunningTime, IMDBVotes, PlotSummary, Plot, ReleasedYear, Language, Country, Genre, Director, Cast, PosterURL, MoviestatusID, MoviequalityID, HostName, HostID, ClientNetAddress, Activity)
				SELECT DateTimeLastModified, LastModifiedByUser, MovieID, DateTimeCreated, CreatedByUser, DateTimeDeleted, DeletedByUser, Title, IMDBID, IMDBRating, RunningTime, IMDBVotes, PlotSummary, Plot, ReleasedYear, Language, Country, Genre, Director, Cast, PosterURL, MoviestatusID, MoviequalityID, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @Activity FROM Inserted
			END

			IF EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted) AND NOT EXISTS(SELECT DateTimeDeleted from Inserted WHERE DateTimeDeleted IS NOT NULL)
			--an actual update (i.e. not a delete)
			BEGIN
				SET @Activity = ''UPDATE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Movies](DateTimeModified, ModifiedByUser, MovieID, DateTimeCreated, CreatedByUser, DateTimeDeleted, DeletedByUser, Title, IMDBID, IMDBRating, RunningTime, IMDBVotes, PlotSummary, Plot, ReleasedYear, Language, Country, Genre, Director, Cast, PosterURL, MoviestatusID, MoviequalityID, HostName, HostID, ClientNetAddress, Activity)
				SELECT DateTimeLastModified, LastModifiedByUser, MovieID, DateTimeCreated, CreatedByUser, DateTimeDeleted, DeletedByUser, Title, IMDBID, IMDBRating, RunningTime, IMDBVotes, PlotSummary, Plot, ReleasedYear, Language, Country, Genre, Director, Cast, PosterURL, MoviestatusID, MoviequalityID, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @Activity FROM Inserted
			END

			IF EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted) AND EXISTS(SELECT DateTimeDeleted from Inserted WHERE DateTimeDeleted IS NOT NULL)
			--actually an update, but functionally a delete
			BEGIN
				SET @Activity = ''DELETE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Movies](DateTimeModified, ModifiedByUser, MovieID, DateTimeCreated, CreatedByUser, DateTimeDeleted, DeletedByUser, Title, IMDBID, IMDBRating, RunningTime, IMDBVotes, PlotSummary, Plot, ReleasedYear, Language, Country, Genre, Director, Cast, PosterURL, MoviestatusID, MoviequalityID, HostName, HostID, ClientNetAddress, Activity)
				SELECT DateTimeLastModified, LastModifiedByUser, MovieID, DateTimeCreated, CreatedByUser, DateTimeDeleted, DeletedByUser, Title, IMDBID, IMDBRating, RunningTime, IMDBVotes, PlotSummary, Plot, ReleasedYear, Language, Country, Genre, Director, Cast, PosterURL, MoviestatusID, MoviequalityID, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @Activity FROM Inserted
			END

			IF NOT EXISTS(SELECT * from Inserted) AND EXISTS(SELECT * from Deleted)
			--an actual delete from the table, per now this is not done, instead updating a row with datetimedeleted and deletedbyuser
			BEGIN
				SET @Activity = ''DELETE'';
				--SET @user = SYSTEM_USER;
				INSERT INTO [hist].[Movies](DateTimeModified, ModifiedByUser, MovieID, DateTimeCreated, CreatedByUser, DateTimeDeleted, DeletedByUser, Title, IMDBID, IMDBRating, RunningTime, IMDBVotes, PlotSummary, Plot, ReleasedYear, Language, Country, Genre, Director, Cast, PosterURL, MoviestatusID, MoviequalityID, HostName, HostID, ClientNetAddress, Activity)
				SELECT DateTimeLastModified, LastModifiedByUser, MovieID, DateTimeCreated, CreatedByUser, DateTimeDeleted, DeletedByUser, Title, IMDBID, IMDBRating, RunningTime, IMDBVotes, PlotSummary, Plot, ReleasedYear, Language, Country, Genre, Director, Cast, PosterURL, MoviestatusID, MoviequalityID, @ModifiedByHostName, @ModifiedByHostID, @ModifiedByClientNetAddress, @Activity FROM Deleted
			END

		END
	');



	
	PRINT 'Adding update privileges on table [dbo].[Errors] to [TitleManagerRole], to ensure triggers are enabled...'

	GRANT UPDATE ON [dbo].[Errors]
		TO [TitleManagerRole];




	PRINT 'Adding update privileges on table [dbo].[Movies] to [TitleManagerRole], to ensure triggers are enabled...'

	GRANT UPDATE ON [dbo].[Movies]
		TO [TitleManagerRole];






	PRINT 'Updating table [dbo].[SchemaChanges] with SchemaChangeNumber...'

	DECLARE @MajorReleaseNumber nvarchar(2) = N'00';
	DECLARE @MinorReleaseNumber nvarchar(2) = N'00';
	DECLARE @PointReleaseNumber nvarchar(4) = N'0004';
	DECLARE @ScriptDescription nvarchar(500) = 'Adds audit triggers. Historical records are now stored in [hist].[Tablename].';
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
