import {
	Box,
	Container,
	Link,
	List,
	ListIcon,
	ListItem,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiCategory } from 'react-icons/bi';
import { GiStairs } from 'react-icons/gi';
import { useQuery } from 'react-query';
import { NavLink } from 'react-router-dom';
import { Table, Tbody, Th, Thead, Tr } from 'react-super-responsive-table';
import EmptyInfo from '../../components/common/EmptyInfo';
import Header from '../../components/common/Header';
import { navActiveStyles, navLinkStyles } from '../../config/styles';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { CourseDifficultySchema } from '../../schemas';
import { SkeletonCourseTaxonomy } from '../../skeleton';
import API from '../../utils/api';
import { isEmpty } from '../../utils/utils';
import DifficultyRow from './components/DifficultyRow';

const AllCourseDifficulties = () => {
	const difficultiesAPI = new API(urls.difficulties);
	const difficultiesQuery = useQuery<CourseDifficultySchema[]>(
		['courseDifficultiesList'],
		() =>
			difficultiesAPI.list({
				per_page: -1,
			})
	);

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header showLinks>
				<List d="flex">
					<ListItem>
						<Link
							as={NavLink}
							sx={navLinkStyles}
							_activeLink={navActiveStyles}
							to={routes.course_categories.list}>
							<ListIcon as={BiCategory} />
							{__('Course Categories', 'masteriyo')}
						</Link>
					</ListItem>
					<ListItem>
						<Link
							as={NavLink}
							sx={navLinkStyles}
							_activeLink={navActiveStyles}
							to={routes.course_difficulties.list}>
							<ListIcon as={GiStairs} />
							{__('Course Difficulties', 'masteriyo')}
						</Link>
					</ListItem>
				</List>
			</Header>
			<Container maxW="container.xl">
				<Stack direction="column" spacing="6">
					<Box bg="white" py="12" shadow="box" mx="auto" w="full">
						<Stack direction="column" spacing="10">
							<Stack direction="column" spacing="8">
								<Table>
									<Thead>
										<Tr>
											<Th>{__('Name', 'masteriyo')}</Th>
											<Th>{__('Slug', 'masteriyo')}</Th>
											<Th>{__('Count', 'masteriyo')}</Th>
											<Th>{__('Actions', 'masteriyo')}</Th>
										</Tr>
									</Thead>
									<Tbody>
										{difficultiesQuery.isLoading ? (
											<SkeletonCourseTaxonomy />
										) : isEmpty(difficultiesQuery?.data) ? (
											<EmptyInfo
												message={__('No difficulties found.', 'masteriyo')}
											/>
										) : (
											difficultiesQuery.data?.map((difficulty) => (
												<DifficultyRow
													key={difficulty.id}
													id={difficulty.id}
													name={difficulty.name}
													slug={difficulty.slug}
													count={difficulty.count}
												/>
											))
										)}
									</Tbody>
								</Table>
							</Stack>
						</Stack>
					</Box>
				</Stack>
			</Container>
		</Stack>
	);
};

export default AllCourseDifficulties;
