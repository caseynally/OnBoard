-- @copyright 2006-2016 City of Bloomington, Indiana
-- @license http://www.gnu.org/copyleft/agpl.html GNU/AGPL, see LICENSE.txt
-- @author Cliff Ingham <inghamn@bloomington.in.gov>
create table races (
	id int unsigned not null primary key auto_increment,
	name varchar(50) not null unique
);
insert races set name='Caucasion';
insert races set name='Hispanic';
insert races set name='African American';
insert races set name='Native American';
insert races set name='Asian';
insert races set name='Other';

create table people (
	id int unsigned not null primary key auto_increment,
	firstname varchar(128) not null,
	lastname varchar(128) not null,
	email varchar(128) unique,
	about text,
	gender enum('male','female'),
	race_id int unsigned,
	username varchar(40) unique,
	password varchar(40),
	authenticationMethod varchar(40),
	role varchar(30),
	foreign key (race_id) references races(id)
);

create table committees (
	id int unsigned not null primary key auto_increment,
	type enum('seated', 'open') not null default 'seated',
	name varchar(128) not null,
	statutoryName varchar(128),
	statuteReference varchar(128),
	statuteUrl varchar(128),
	yearFormed year(4),
	website varchar(128),
	email   varchar(128),
	phone   varchar(128),
	address varchar(128),
	city    varchar(128),
	state   varchar(32),
	zip     varchar(32),
    description     text,
	contactInfo     text,
	meetingSchedule text
);

create table appointers (
	id int unsigned not null primary key auto_increment,
	name varchar(128) not null unique
);
insert appointers values(1,'Elected');

--
-- Begin 2.0 changes
--
create table allocations (
	id int unsigned not null primary key auto_increment,
	name varchar(128) not null,
	committee_id int unsigned not null,
	appointer_id int unsigned,
	startDate date not null,
	endDate   date,
	requirements text,
	foreign key (appointer_id) references appointers(id),
	foreign key (committee_id) references committees(id)
);

create table seats (
    id int unsigned not null primary key auto_increment,
    type enum('termed', 'open'),
    name varchar(128) not null,
    allocation_id int unsigned not null,
    startDate date,
    endDate   date,
    foreign key (allocation_id) references allocations(id)
);

create table terms (
    id      int unsigned not null primary key auto_increment,
	seat_id int unsigned,
	startDate date not null,
	endDate   date not null,
	foreign key (seat_id) references seats(id)
);

create table members (
	id int unsigned not null primary key auto_increment,
	committee_id int unsigned not null,
	term_id      int unsigned,
	person_id    int unsigned not null,
	startDate date,
	endDate   date,
	foreign key (committee_id) references committees(id),
	foreign key (term_id)      references terms     (id),
	foreign key (person_id)    references people    (id)
);
--
-- End 2.0 changes
--

create table offices (
	id int unsigned not null primary key auto_increment,
	committee_id int unsigned not null,
	person_id int unsigned not null,
	title varchar(128) not null,
	startDate date not null,
	endDate date,
	foreign key (committee_id) references committees(id),
	foreign key (person_id) references people(id)
);

create table topicTypes (
	id int unsigned not null primary key auto_increment,
	name varchar(128) not null
);

create table topics (
	id int unsigned not null primary key auto_increment,
	topicType_id int unsigned not null,
	date date not null,
	number varchar(25) not null,
	description text not null,
	synopsis text not null,
	committee_id int unsigned not null,
	foreign key (topicType_id) references topicTypes(id),
	foreign key (committee_id) references committees(id)
);

create table voteTypes (
	id int unsigned not null primary key auto_increment,
	name varchar(128) not null,
	ordering tinyint unsigned not null,
	unique (ordering)
);

create table votes (
	id int unsigned not null primary key auto_increment,
	date date not null,
	voteType_id int unsigned not null,
	topic_id int unsigned not null,
	outcome enum('pass','fail'),
	foreign key (voteType_id) references voteTypes(id),
	foreign key (topic_id) references topics(id)
);

create table votingRecords (
	id int unsigned not null primary key auto_increment,
	term_id int unsigned not null,
	vote_id int unsigned not null,
	position enum('yes','no','abstain','absent') not null default 'absent',
	foreign key (term_id) references terms(id),
	foreign key (vote_id)  references votes(id)
);

create table tags (
	id int unsigned not null primary key auto_increment,
	name varchar(128) not null
);

create table topic_tags (
	topic_id int unsigned not null,
	tag_id int unsigned not null,
	foreign key (topic_id) references topics(id),
	foreign key (tag_id) references tags(id)
);
