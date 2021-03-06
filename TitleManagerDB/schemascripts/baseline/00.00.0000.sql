

SET NOCOUNT ON;
IF EXISTS(SELECT DB_NAME() WHERE DB_NAME() NOT IN ('master')) USE master;

BEGIN TRY
	PRINT 'Creating database ''$(DBNAME)''...';
	IF EXISTS(SELECT * FROM sys.databases WHERE NAME='$(DBNAME)')
	BEGIN
		PRINT 'A database with the name ''$(DBNAME)'' already exists. Dropping and recreating...';
		DROP DATABASE $(DBNAME)
	END
	CREATE DATABASE $(DBNAME);
	
END TRY
BEGIN CATCH
	IF (@@TRANCOUNT > 0) ROLLBACK TRANSACTION
	DECLARE @ERROR_MSG1 nvarchar(2048) = error_message()
	DECLARE @ERROR_SEVERITY1 nvarchar(2048) = error_severity()
	DECLARE @ERROR_STATE1 nvarchar(2048) = error_state()
	RAISERROR (@ERROR_MSG1, @ERROR_SEVERITY1, @ERROR_STATE1)
END CATCH
GO

BEGIN TRY
	BEGIN TRANSACTION	

	IF EXISTS(SELECT DB_NAME() WHERE DB_NAME() NOT IN ('$(DBNAME)')) USE $(DBNAME);

	/****** Object:  User [TitleManagerUser]    Script Date: 180301 15:59:38 ******/
	PRINT 'Creating user [TitleManagerUser] ...'

	CREATE USER [TitleManagerUser] FOR LOGIN [TitleManagerLogin] WITH DEFAULT_SCHEMA=[dbo]

	ALTER ROLE [db_datareader] ADD MEMBER [TitleManagerUser]

	ALTER ROLE [db_datawriter] ADD MEMBER [TitleManagerUser]

	/****** Object:  Table [dbo].[Loaners]    Script Date: 180301 15:59:38 ******/
	SET ANSI_NULLS ON

	SET QUOTED_IDENTIFIER ON


	PRINT 'Creating table [dbo].[Loaners]...'

	CREATE TABLE [dbo].[Loaners](
		[LoanerID] [int] IDENTITY(1,1) NOT NULL,
		[Description] [nvarchar](100) NOT NULL,
	 CONSTRAINT [PK_Loaners] PRIMARY KEY CLUSTERED 
	(
		[LoanerID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]

	/****** Object:  Table [dbo].[Missingmovies]    Script Date: 180301 15:59:39 ******/
	SET ANSI_NULLS ON

	SET QUOTED_IDENTIFIER ON


	PRINT 'Creating table [dbo].[Missingmovies]...'

	CREATE TABLE [dbo].[Missingmovies](
		[MissingmovieID] [int] IDENTITY(1,1) NOT NULL,
		[MovieID] [int] NOT NULL,
		[RegisteredByUser] [int] NOT NULL,
		[DateTimeMissing] [datetime2](7) NOT NULL,
		[DateTimeReturn] [datetime2](7) NULL,
	 CONSTRAINT [PK_Missingmovies] PRIMARY KEY CLUSTERED 
	(
		[MissingmovieID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]

	/****** Object:  Table [dbo].[Movieloans]    Script Date: 180301 15:59:39 ******/
	SET ANSI_NULLS ON

	SET QUOTED_IDENTIFIER ON


	PRINT 'Creating table [dbo].[Movieloans]...'

	CREATE TABLE [dbo].[Movieloans](
		[MovieloanID] [int] IDENTITY(1,1) NOT NULL,
		[MovieID] [int] NOT NULL,
		[LoanerID] [int] NOT NULL,
		[RegisteredByUser] [int] NOT NULL,
		[DateTimeLoan] [datetime2](7) NOT NULL,
		[DateTimeReturn] [datetime2](7) NULL,
	 CONSTRAINT [PK_Movieloans] PRIMARY KEY CLUSTERED 
	(
		[MovieloanID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]

	/****** Object:  Table [dbo].[Moviequalities]    Script Date: 180301 15:59:39 ******/
	SET ANSI_NULLS ON

	SET QUOTED_IDENTIFIER ON


	PRINT 'Creating table [dbo].[Moviequalities]...'

	CREATE TABLE [dbo].[Moviequalities](
		[MoviequalityID] [int] IDENTITY(1,1) NOT NULL,
		[Description] [nvarchar](100) NOT NULL,
	 CONSTRAINT [PK_Moviequality] PRIMARY KEY CLUSTERED 
	(
		[MoviequalityID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]

	/****** Object:  Table [dbo].[Movies]    Script Date: 180301 15:59:39 ******/
	SET ANSI_NULLS ON

	SET QUOTED_IDENTIFIER ON


	PRINT 'Creating table [dbo].[Movies]...'

	CREATE TABLE [dbo].[Movies](
		[MovieID] [int] IDENTITY(1,1) NOT NULL,
		[DateTimeCreated] [datetime2](7) NOT NULL,
		[CreatedByUser] [int] NOT NULL,
		[DateTimeDeleted] [datetime2](7) NULL,
		[DeletedByUser] [int] NULL,
		[Title] [nvarchar](100) NOT NULL,
		[IMDBID] [nvarchar](50) NULL,
		[IMDBRating] [float] NULL,
		[RunningTime] [int] NULL,
		[IMDBVotes] [int] NULL,
		[PlotSummary] [text] NULL,
		[Plot] [text] NULL,
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
		[MovieID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

	/****** Object:  Table [dbo].[Moviesortings]    Script Date: 180301 15:59:39 ******/
	SET ANSI_NULLS ON

	SET QUOTED_IDENTIFIER ON


	PRINT 'Creating table [dbo].[Moviesortings]...'

	CREATE TABLE [dbo].[Moviesortings](
		[MoviesortingID] [int] IDENTITY(1,1) NOT NULL,
		[Description] [nvarchar](50) NOT NULL,
		[SortType] [nvarchar](50) NULL,
	 CONSTRAINT [PK_Moviesorting] PRIMARY KEY CLUSTERED 
	(
		[MoviesortingID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]

	/****** Object:  Table [dbo].[Moviestatuses]    Script Date: 180301 15:59:39 ******/
	SET ANSI_NULLS ON

	SET QUOTED_IDENTIFIER ON


	PRINT 'Creating table [dbo].[Moviestatuses]...'

	CREATE TABLE [dbo].[Moviestatuses](
		[MoviestatusID] [int] IDENTITY(1,1) NOT NULL,
		[Description] [nvarchar](100) NOT NULL,
	 CONSTRAINT [PK_Moviestatus] PRIMARY KEY CLUSTERED 
	(
		[MoviestatusID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]

	/****** Object:  Table [dbo].[Pages_UserRoles]    Script Date: 180301 15:59:39 ******/
	SET ANSI_NULLS ON

	SET QUOTED_IDENTIFIER ON


	PRINT 'Creating table [dbo].[Pages_UserRoles]...'

	CREATE TABLE [dbo].[Pages_UserRoles](
		[Pages_UserRolesID] [int] IDENTITY(1,1) NOT NULL,
		[PageID] [int] NOT NULL,
		[UserRoleID] [int] NOT NULL,
	 CONSTRAINT [PK_Pages_UserRoles] PRIMARY KEY CLUSTERED 
	(
		[Pages_UserRolesID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]

	/****** Object:  Table [dbo].[Posters]    Script Date: 180301 15:59:39 ******/
	SET ANSI_NULLS ON

	SET QUOTED_IDENTIFIER ON


	PRINT 'Creating table [dbo].[Posters]...'

	CREATE TABLE [dbo].[Posters](
		[PosterID] [int] IDENTITY(1,1) NOT NULL,
		[DateTimeCreated] [datetime2](7) NOT NULL,
		[CreatedByUser] [int] NOT NULL,
		[MovieID] [int] NOT NULL,
		[Filename] [nvarchar](100) NOT NULL,
		[Type] [nvarchar](50) NOT NULL,
		[Size] [int] NOT NULL,
		[MouseoverTitle] [nvarchar](100) NOT NULL,
	 CONSTRAINT [PK_Posters] PRIMARY KEY CLUSTERED 
	(
		[PosterID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]

	/****** Object:  Table [dbo].[SchemaChanges]    Script Date: 180301 15:59:39 ******/
	SET ANSI_NULLS ON

	SET QUOTED_IDENTIFIER ON


	PRINT 'Creating table [dbo].[SchemaChanges]...'

	CREATE TABLE [dbo].[SchemaChanges](
		[SchemaChangeID] [int] IDENTITY(1,1) NOT NULL,
		[MajorReleaseNumber] [nvarchar](2) NOT NULL,
		[MinorReleaseNumber] [nvarchar](2) NOT NULL,
		[PointReleaseNumber] [nvarchar](4) NOT NULL,
		[ScriptDescription] [nvarchar](50) NOT NULL,
		[DateTimeApplied] [datetime2](7) NOT NULL,
	 CONSTRAINT [PK_SchemaChanges] PRIMARY KEY CLUSTERED 
	(
		[SchemaChangeID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]

	/****** Object:  Table [dbo].[Subjects_UserRoles]    Script Date: 180301 15:59:39 ******/
	SET ANSI_NULLS ON

	SET QUOTED_IDENTIFIER ON


	PRINT 'Creating table [dbo].[Subjects_UserRoles]...'

	CREATE TABLE [dbo].[Subjects_UserRoles](
		[Subjects_UserRolesID] [int] IDENTITY(1,1) NOT NULL,
		[SubjectID] [int] NOT NULL,
		[UserRoleID] [int] NOT NULL,
	 CONSTRAINT [PK_Subjects_UserRoles] PRIMARY KEY CLUSTERED 
	(
		[Subjects_UserRolesID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]

	/****** Object:  Table [dbo].[Web_Errors]    Script Date: 180301 15:59:39 ******/
	SET ANSI_NULLS ON

	SET QUOTED_IDENTIFIER ON


	PRINT 'Creating table [dbo].[Web_Errors]...'

	CREATE TABLE [dbo].[Web_Errors](
		[ErrorID] [int] IDENTITY(1,1) NOT NULL,
		[DateTimeLog] [datetime2](7) NOT NULL,
		[ErrorMessage] [nvarchar](1000) NOT NULL,
		[ExceptionMessage] [nvarchar](1000) NULL,
		[ExceptionCode] [int] NULL,
		[ExceptionTrace] [nvarchar](1000) NULL,
	 CONSTRAINT [PK_Web_Errors] PRIMARY KEY CLUSTERED 
	(
		[ErrorID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]

	/****** Object:  Table [dbo].[Web_Logins]    Script Date: 180301 15:59:39 ******/
	SET ANSI_NULLS ON

	SET QUOTED_IDENTIFIER ON


	PRINT 'Creating table [dbo].[Web_Logins]...'

	CREATE TABLE [dbo].[Web_Logins](
		[Web_LoginID] [int] IDENTITY(1,1) NOT NULL,
		[UserID] [int] NOT NULL,
		[DateTimeLogin] [datetime2](7) NOT NULL,
		[DateTimeLastActivity] [datetime2](7) NOT NULL,
	 CONSTRAINT [PK_Web_Logins] PRIMARY KEY CLUSTERED 
	(
		[Web_LoginID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]

	/****** Object:  Table [dbo].[Web_Pages]    Script Date: 180301 15:59:39 ******/
	SET ANSI_NULLS ON

	SET QUOTED_IDENTIFIER ON


	PRINT 'Creating table [dbo].[Web_Pages]...'

	CREATE TABLE [dbo].[Web_Pages](
		[PageID] [int] IDENTITY(1,1) NOT NULL,
		[SubjectID] [int] NOT NULL,
		[MenuName] [nvarchar](50) NOT NULL,
		[Position] [int] NULL,
		[Visible] [bit] NULL,
		[Contents] [nvarchar](1000) NULL,
		[Admin] [bit] NOT NULL,
	 CONSTRAINT [PK_Web.Page] PRIMARY KEY CLUSTERED 
	(
		[PageID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]

	/****** Object:  Table [dbo].[Web_Subjects]    Script Date: 180301 15:59:39 ******/
	SET ANSI_NULLS ON

	SET QUOTED_IDENTIFIER ON


	PRINT 'Creating table [dbo].[Web_Subjects]...'

	CREATE TABLE [dbo].[Web_Subjects](
		[SubjectID] [int] IDENTITY(1,1) NOT NULL,
		[MenuName] [nvarchar](50) NOT NULL,
		[Position] [int] NULL,
		[Visible] [bit] NULL,
		[Admin] [bit] NOT NULL,
	 CONSTRAINT [PK_Web.Subject] PRIMARY KEY CLUSTERED 
	(
		[SubjectID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]

	/****** Object:  Table [dbo].[Web_UserRoles]    Script Date: 180301 15:59:39 ******/
	SET ANSI_NULLS ON

	SET QUOTED_IDENTIFIER ON


	PRINT 'Creating table [dbo].[Web_UserRoles]...'

	CREATE TABLE [dbo].[Web_UserRoles](
		[UserRoleID] [int] IDENTITY(1,1) NOT NULL,
		[UserRoleName] [nvarchar](50) NOT NULL,
	 CONSTRAINT [PK_UserRoles] PRIMARY KEY CLUSTERED 
	(
		[UserRoleID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]

	/****** Object:  Table [dbo].[Web_Users]    Script Date: 180301 15:59:39 ******/
	SET ANSI_NULLS ON

	SET QUOTED_IDENTIFIER ON


	PRINT 'Creating table [dbo].[Web_Users]...'

	CREATE TABLE [dbo].[Web_Users](
		[UserID] [int] IDENTITY(1,1) NOT NULL,
		[Username] [nvarchar](50) NOT NULL,
		[HashedPassword] [nvarchar](60) NOT NULL,
		[UserRoleID] [int] NOT NULL,
		[DateTimeCreated] [datetime2](7) NOT NULL,
	 CONSTRAINT [PK_user] PRIMARY KEY CLUSTERED 
	(
		[UserID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]


	PRINT 'Populating table [dbo].[Moviequalities] with static data...'

	SET IDENTITY_INSERT [dbo].[Moviequalities] ON 
	INSERT [dbo].[Moviequalities] ([MoviequalityID], [Description]) VALUES (1, N'Blu-ray')
	INSERT [dbo].[Moviequalities] ([MoviequalityID], [Description]) VALUES (2, N'DVD')
	INSERT [dbo].[Moviequalities] ([MoviequalityID], [Description]) VALUES (3, N'Other')
	INSERT [dbo].[Moviequalities] ([MoviequalityID], [Description]) VALUES (4, N'Unknown')
	SET IDENTITY_INSERT [dbo].[Moviequalities] OFF


	PRINT 'Populating table [dbo].[Moviesortings] with static data...'

	SET IDENTITY_INSERT [dbo].[Moviesortings] ON 
	INSERT [dbo].[Moviesortings] ([MoviesortingID], [Description], [SortType]) VALUES (1, N'A-Z', N'Title ASC')
	INSERT [dbo].[Moviesortings] ([MoviesortingID], [Description], [SortType]) VALUES (2, N'Z-A', N'Title DESC')
	INSERT [dbo].[Moviesortings] ([MoviesortingID], [Description], [SortType]) VALUES (3, N'IMDB Rating', N'IMDBRating DESC')
	INSERT [dbo].[Moviesortings] ([MoviesortingID], [Description], [SortType]) VALUES (4, N'IMDB Votes', N'IMDBVotes DESC')
	SET IDENTITY_INSERT [dbo].[Moviesortings] OFF

	PRINT 'Populating table [dbo].[Moviestatuses] with static data...'

	SET IDENTITY_INSERT [dbo].[Moviestatuses] ON 
	INSERT [dbo].[Moviestatuses] ([MoviestatusID], [Description]) VALUES (1, N'In library')
	INSERT [dbo].[Moviestatuses] ([MoviestatusID], [Description]) VALUES (2, N'Loaned out')
	INSERT [dbo].[Moviestatuses] ([MoviestatusID], [Description]) VALUES (3, N'Missing')
	INSERT [dbo].[Moviestatuses] ([MoviestatusID], [Description]) VALUES (4, N'Other')
	INSERT [dbo].[Moviestatuses] ([MoviestatusID], [Description]) VALUES (5, N'Unknown')
	SET IDENTITY_INSERT [dbo].[Moviestatuses] OFF

	PRINT 'Populating table [dbo].[Pages_UserRoles] with static data...'

	SET IDENTITY_INSERT [dbo].[Pages_UserRoles] ON 
	INSERT [dbo].[Pages_UserRoles] ([Pages_UserRolesID], [PageID], [UserRoleID]) VALUES (1, 1, 1)
	INSERT [dbo].[Pages_UserRoles] ([Pages_UserRolesID], [PageID], [UserRoleID]) VALUES (2, 2, 1)
	INSERT [dbo].[Pages_UserRoles] ([Pages_UserRolesID], [PageID], [UserRoleID]) VALUES (3, 3, 1)
	INSERT [dbo].[Pages_UserRoles] ([Pages_UserRolesID], [PageID], [UserRoleID]) VALUES (4, 4, 1)
	SET IDENTITY_INSERT [dbo].[Pages_UserRoles] OFF

	PRINT 'Populating table [dbo].[Subjects_UserRoles] with static data...'

	SET IDENTITY_INSERT [dbo].[Subjects_UserRoles] ON 
	INSERT [dbo].[Subjects_UserRoles] ([Subjects_UserRolesID], [SubjectID], [UserRoleID]) VALUES (1, 1, 1)
	INSERT [dbo].[Subjects_UserRoles] ([Subjects_UserRolesID], [SubjectID], [UserRoleID]) VALUES (2, 2, 1)
	INSERT [dbo].[Subjects_UserRoles] ([Subjects_UserRolesID], [SubjectID], [UserRoleID]) VALUES (3, 3, 2)
	SET IDENTITY_INSERT [dbo].[Subjects_UserRoles] OFF

	PRINT 'Populating table [dbo].[Web_Pages] with static data...'

	SET IDENTITY_INSERT [dbo].[Web_Pages] ON 
	INSERT [dbo].[Web_Pages] ([PageID], [SubjectID], [MenuName], [Position], [Visible], [Contents], [Admin]) VALUES (1, 2, N'SQL Server', 3, 1, NULL, 1)
	INSERT [dbo].[Web_Pages] ([PageID], [SubjectID], [MenuName], [Position], [Visible], [Contents], [Admin]) VALUES (2, 2, N'PHP', 2, 1, NULL, 1)
	INSERT [dbo].[Web_Pages] ([PageID], [SubjectID], [MenuName], [Position], [Visible], [Contents], [Admin]) VALUES (3, 2, N'Users', 1, 1, NULL, 1)
	INSERT [dbo].[Web_Pages] ([PageID], [SubjectID], [MenuName], [Position], [Visible], [Contents], [Admin]) VALUES (4, 2, N'OS & Browser', 1, 1, NULL, 1)
	INSERT [dbo].[Web_Pages] ([PageID], [SubjectID], [MenuName], [Position], [Visible], [Contents], [Admin]) VALUES (5, 3, N'Browse', 2, 1, NULL, 0)
	INSERT [dbo].[Web_Pages] ([PageID], [SubjectID], [MenuName], [Position], [Visible], [Contents], [Admin]) VALUES (6, 3, N'Search', 1, 0, NULL, 0)
	INSERT [dbo].[Web_Pages] ([PageID], [SubjectID], [MenuName], [Position], [Visible], [Contents], [Admin]) VALUES (7, 3, N'Add', 3, 1, NULL, 0)
	INSERT [dbo].[Web_Pages] ([PageID], [SubjectID], [MenuName], [Position], [Visible], [Contents], [Admin]) VALUES (8, 3, N'Loans', 4, 1, NULL, 0)
	SET IDENTITY_INSERT [dbo].[Web_Pages] OFF

	PRINT 'Populating table [dbo].[Web_Subjects] with static data...'

	SET IDENTITY_INSERT [dbo].[Web_Subjects] ON 
	INSERT [dbo].[Web_Subjects] ([SubjectID], [MenuName], [Position], [Visible], [Admin]) VALUES (1, N'About', 1, 0, 1)
	INSERT [dbo].[Web_Subjects] ([SubjectID], [MenuName], [Position], [Visible], [Admin]) VALUES (2, N'Admin', 2, 1, 1)
	INSERT [dbo].[Web_Subjects] ([SubjectID], [MenuName], [Position], [Visible], [Admin]) VALUES (3, N'Movies', 3, 1, 0)
	SET IDENTITY_INSERT [dbo].[Web_Subjects] OFF

	PRINT 'Populating table [dbo].[Web_UserRoles] with static data...'

	SET IDENTITY_INSERT [dbo].[Web_UserRoles] ON 
	INSERT [dbo].[Web_UserRoles] ([UserRoleID], [UserRoleName]) VALUES (1, N'admin')
	INSERT [dbo].[Web_UserRoles] ([UserRoleID], [UserRoleName]) VALUES (2, N'user')
	SET IDENTITY_INSERT [dbo].[Web_UserRoles] OFF

	SET ANSI_PADDING ON

	/****** Object:  Index [IX_Web_Users]    Script Date: 180301 15:59:39 ******/

	PRINT 'Adding indexes and constraints(?)...'

	ALTER TABLE [dbo].[Web_Users] ADD  CONSTRAINT [IX_Web_Users] UNIQUE NONCLUSTERED 
	(
		[Username] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]

	ALTER TABLE [dbo].[Movies] ADD  CONSTRAINT [DF_Movies_CreatedByUser]  DEFAULT ((0)) FOR [CreatedByUser]

	ALTER TABLE [dbo].[Movies] ADD  CONSTRAINT [DF_Movies_IMDBID]  DEFAULT ((0)) FOR [IMDBID]

	ALTER TABLE [dbo].[Movies] ADD  CONSTRAINT [DF_Movies_IMDBRating]  DEFAULT ((0)) FOR [IMDBRating]

	ALTER TABLE [dbo].[Movies] ADD  CONSTRAINT [DF_Movies_RunningTime]  DEFAULT ((0)) FOR [RunningTime]

	ALTER TABLE [dbo].[Movies] ADD  CONSTRAINT [DF_Movies_IMDBVotes]  DEFAULT ((0)) FOR [IMDBVotes]

	ALTER TABLE [dbo].[Movies] ADD  CONSTRAINT [DF_Movies_Plot]  DEFAULT ('No plot entered.') FOR [Plot]

	ALTER TABLE [dbo].[Movies] ADD  CONSTRAINT [DF_Movies_ReleasedYear]  DEFAULT ((0)) FOR [ReleasedYear]

	ALTER TABLE [dbo].[Movies] ADD  CONSTRAINT [DF_Movies_MoviestatusID]  DEFAULT ((1)) FOR [MoviestatusID]

	ALTER TABLE [dbo].[Movies] ADD  CONSTRAINT [DF_Movies_QualityID]  DEFAULT ((1)) FOR [MoviequalityID]

	ALTER TABLE [dbo].[SchemaChanges] ADD  CONSTRAINT [DF_SchemaChanges_DateTimeApplied]  DEFAULT (sysdatetime()) FOR [DateTimeApplied]

	ALTER TABLE [dbo].[Web_Pages] ADD  CONSTRAINT [DF_Web_Pages_Admin]  DEFAULT ((1)) FOR [Admin]

	ALTER TABLE [dbo].[Web_Subjects] ADD  CONSTRAINT [DF_Web_Subjects_Admin]  DEFAULT ((1)) FOR [Admin]

	ALTER TABLE [dbo].[Web_Users] ADD  CONSTRAINT [DF_Web_User_UserRole]  DEFAULT ((2)) FOR [UserRoleID]

	ALTER TABLE [dbo].[Web_Users] ADD  CONSTRAINT [DF_Web_Users_DateCreated]  DEFAULT (getdate()) FOR [DateTimeCreated]

	ALTER TABLE [dbo].[Missingmovies]  WITH CHECK ADD  CONSTRAINT [FK_Missingmovies_Movies] FOREIGN KEY([MovieID])
	REFERENCES [dbo].[Movies] ([MovieID])

	ALTER TABLE [dbo].[Missingmovies] CHECK CONSTRAINT [FK_Missingmovies_Movies]

	ALTER TABLE [dbo].[Missingmovies]  WITH CHECK ADD  CONSTRAINT [FK_Missingmovies_Web_Users] FOREIGN KEY([RegisteredByUser])
	REFERENCES [dbo].[Web_Users] ([UserID])

	ALTER TABLE [dbo].[Missingmovies] CHECK CONSTRAINT [FK_Missingmovies_Web_Users]

	ALTER TABLE [dbo].[Movieloans]  WITH CHECK ADD  CONSTRAINT [FK_Movieloans_Loaners] FOREIGN KEY([LoanerID])
	REFERENCES [dbo].[Loaners] ([LoanerID])

	ALTER TABLE [dbo].[Movieloans] CHECK CONSTRAINT [FK_Movieloans_Loaners]

	ALTER TABLE [dbo].[Movieloans]  WITH CHECK ADD  CONSTRAINT [FK_Movieloans_Movies] FOREIGN KEY([MovieID])
	REFERENCES [dbo].[Movies] ([MovieID])

	ALTER TABLE [dbo].[Movieloans] CHECK CONSTRAINT [FK_Movieloans_Movies]

	ALTER TABLE [dbo].[Movieloans]  WITH CHECK ADD  CONSTRAINT [FK_Movieloans_Web_Users] FOREIGN KEY([RegisteredByUser])
	REFERENCES [dbo].[Web_Users] ([UserID])

	ALTER TABLE [dbo].[Movieloans] CHECK CONSTRAINT [FK_Movieloans_Web_Users]

	ALTER TABLE [dbo].[Movies]  WITH CHECK ADD  CONSTRAINT [FK_Movies_Moviequality] FOREIGN KEY([MoviequalityID])
	REFERENCES [dbo].[Moviequalities] ([MoviequalityID])

	ALTER TABLE [dbo].[Movies] CHECK CONSTRAINT [FK_Movies_Moviequality]

	ALTER TABLE [dbo].[Movies]  WITH CHECK ADD  CONSTRAINT [FK_Movies_Moviestatus] FOREIGN KEY([MoviestatusID])
	REFERENCES [dbo].[Moviestatuses] ([MoviestatusID])

	ALTER TABLE [dbo].[Movies] CHECK CONSTRAINT [FK_Movies_Moviestatus]

	ALTER TABLE [dbo].[Movies]  WITH CHECK ADD  CONSTRAINT [FK_Movies_Web_Users] FOREIGN KEY([CreatedByUser])
	REFERENCES [dbo].[Web_Users] ([UserID])

	ALTER TABLE [dbo].[Movies] CHECK CONSTRAINT [FK_Movies_Web_Users]

	ALTER TABLE [dbo].[Movies]  WITH CHECK ADD  CONSTRAINT [FK_Movies_Web_Users1] FOREIGN KEY([DeletedByUser])
	REFERENCES [dbo].[Web_Users] ([UserID])

	ALTER TABLE [dbo].[Movies] CHECK CONSTRAINT [FK_Movies_Web_Users1]

	ALTER TABLE [dbo].[Pages_UserRoles]  WITH CHECK ADD  CONSTRAINT [FK_Pages_UserRoles_Web_Pages] FOREIGN KEY([PageID])
	REFERENCES [dbo].[Web_Pages] ([PageID])

	ALTER TABLE [dbo].[Pages_UserRoles] CHECK CONSTRAINT [FK_Pages_UserRoles_Web_Pages]

	ALTER TABLE [dbo].[Pages_UserRoles]  WITH CHECK ADD  CONSTRAINT [FK_Pages_UserRoles_Web_UserRoles] FOREIGN KEY([UserRoleID])
	REFERENCES [dbo].[Web_UserRoles] ([UserRoleID])

	ALTER TABLE [dbo].[Pages_UserRoles] CHECK CONSTRAINT [FK_Pages_UserRoles_Web_UserRoles]

	ALTER TABLE [dbo].[Posters]  WITH CHECK ADD  CONSTRAINT [FK_Posters_Movies] FOREIGN KEY([MovieID])
	REFERENCES [dbo].[Movies] ([MovieID])

	ALTER TABLE [dbo].[Posters] CHECK CONSTRAINT [FK_Posters_Movies]

	ALTER TABLE [dbo].[Posters]  WITH CHECK ADD  CONSTRAINT [FK_Posters_Web_Users] FOREIGN KEY([CreatedByUser])
	REFERENCES [dbo].[Web_Users] ([UserID])

	ALTER TABLE [dbo].[Posters] CHECK CONSTRAINT [FK_Posters_Web_Users]

	ALTER TABLE [dbo].[Subjects_UserRoles]  WITH CHECK ADD  CONSTRAINT [FK_Subjects_UserRoles_Web_Subjects] FOREIGN KEY([SubjectID])
	REFERENCES [dbo].[Web_Subjects] ([SubjectID])

	ALTER TABLE [dbo].[Subjects_UserRoles] CHECK CONSTRAINT [FK_Subjects_UserRoles_Web_Subjects]

	ALTER TABLE [dbo].[Subjects_UserRoles]  WITH CHECK ADD  CONSTRAINT [FK_Subjects_UserRoles_Web_UserRoles] FOREIGN KEY([UserRoleID])
	REFERENCES [dbo].[Web_UserRoles] ([UserRoleID])

	ALTER TABLE [dbo].[Subjects_UserRoles] CHECK CONSTRAINT [FK_Subjects_UserRoles_Web_UserRoles]

	ALTER TABLE [dbo].[Web_Logins]  WITH CHECK ADD  CONSTRAINT [FK_Web_Logins_Web_Users] FOREIGN KEY([UserID])
	REFERENCES [dbo].[Web_Users] ([UserID])

	ALTER TABLE [dbo].[Web_Logins] CHECK CONSTRAINT [FK_Web_Logins_Web_Users]

	ALTER TABLE [dbo].[Web_Pages]  WITH CHECK ADD  CONSTRAINT [FK_Web_Pages_Web_Subjects] FOREIGN KEY([SubjectID])
	REFERENCES [dbo].[Web_Subjects] ([SubjectID])

	ALTER TABLE [dbo].[Web_Pages] CHECK CONSTRAINT [FK_Web_Pages_Web_Subjects]

	ALTER TABLE [dbo].[Web_Users]  WITH CHECK ADD  CONSTRAINT [FK_Web_Users_Web_UserRoles] FOREIGN KEY([UserRoleID])
	REFERENCES [dbo].[Web_UserRoles] ([UserRoleID])

	ALTER TABLE [dbo].[Web_Users] CHECK CONSTRAINT [FK_Web_Users_Web_UserRoles]


	/****** Object:  StoredProcedure [dbo].[find_movie_by_imdbid]    Script Date: 180301 15:59:39 ******/
	SET ANSI_NULLS ON

	SET QUOTED_IDENTIFIER ON

	PRINT 'Creating schema [sp]...'

	EXEC('CREATE SCHEMA [sp]')
	
	PRINT 'Creating stored procedure [sp].[find_movie_by_imdbid]...'

	EXEC('
		CREATE PROCEDURE [sp].[find_movie_by_imdbid] 
			@imdbid nvarchar(50)
		AS
		BEGIN
			-- SET NOCOUNT ON added to prevent extra result sets from
			-- interfering with SELECT statements.
			SET NOCOUNT ON;

			-- Insert statements for procedure here
			SELECT * FROM dbo.Movies WHERE IMDBID = @imdbid
		END
	')

	
	PRINT 'Creating role [TitleManagerRole]...'

	CREATE ROLE [TitleManagerRole]

	PRINT 'Adding user [TitleManagerUser] to role [TitleManagerRole]...'

	ALTER ROLE [TitleManagerRole] ADD MEMBER [TitleManagerUser]
	

	PRINT 'Adding execute privileges on schema [sp] to [TitleManagerRole]...'

	GRANT EXECUTE ON SCHEMA::[sp]  
		TO [TitleManagerRole];  
  



  	PRINT 'Updating table [dbo].[SchemaChanges] with SchemaChangeNumber...'

	SET IDENTITY_INSERT [dbo].[SchemaChanges] ON
	INSERT [dbo].[SchemaChanges] ([SchemaChangeID], [MajorReleaseNumber], [MinorReleaseNumber], [PointReleaseNumber], [ScriptDescription], [DateTimeApplied]) VALUES (1, N'00', N'00', N'0000', 'Baseline', SYSDATETIME())
	SET IDENTITY_INSERT [dbo].[SchemaChanges] OFF


	COMMIT TRANSACTION
END TRY
BEGIN CATCH
	IF (@@TRANCOUNT > 0) ROLLBACK TRANSACTION
	DECLARE @ERROR_MSG nvarchar(2048) = error_message()
	DECLARE @ERROR_SEVERITY nvarchar(2048) = error_severity()
	DECLARE @ERROR_STATE nvarchar(2048) = error_state()
	
	IF EXISTS(SELECT * FROM sys.databases WHERE NAME='$(DBNAME)')
	BEGIN
		USE master;
		PRINT 'Error encountered. Dropping database ''$(DBNAME)''...';
		DROP DATABASE $(DBNAME)
	END
	
	RAISERROR (@ERROR_MSG, @ERROR_SEVERITY, @ERROR_STATE)
END CATCH
GO

SET NOCOUNT OFF;
GO

