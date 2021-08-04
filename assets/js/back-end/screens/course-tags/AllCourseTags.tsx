import {
	Box,
	Container,
	Heading,
	Stack,
	Table,
	Tbody,
	Th,
	Thead,
	Tr,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useQuery } from 'react-query';
import PageNav from '../../components/common/PageNav';
import { tableStyles } from '../../config/styles';
import urls from '../../constants/urls';
import { SkeletonCourseTaxonomy } from '../../skeleton';
import API from '../../utils/api';
import Header from './components/Header';
import ListRow from './components/ListRow';

const AllCourseTags = () => {
	const tagsAPI = new API(urls.tags);
	const tagsQuery = useQuery('courseTagsList', () => tagsAPI.list());

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header />
			<Container maxW="container.xl">
				<Stack direction="column" spacing="6">
					<PageNav currentTitle={__('Tags', 'masteriyo')} />
					<Box bg="white" py="12" shadow="box" mx="auto">
						<Stack direction="column" spacing="8">
							<Box px="12">
								<Heading as="h1" size="lg">
									{__('Tags', 'masteriyo')}
								</Heading>
							</Box>
							<Stack direction="column" spacing="8">
								<Table size="sm" sx={tableStyles}>
									<Thead>
										<Tr>
											<Th>{__('Name', 'masteriyo')}</Th>
											<Th>{__('Description', 'masteriyo')}</Th>
											<Th>{__('Slug', 'masteriyo')}</Th>
											<Th>{__('Count', 'masteriyo')}</Th>
											<Th>{__('Actions', 'masteriyo')}</Th>
										</Tr>
									</Thead>
									<Tbody>
										{tagsQuery.isLoading && <SkeletonCourseTaxonomy />}
										{tagsQuery.isSuccess &&
											tagsQuery.data.map((cat: any) => (
												<ListRow
													key={cat.id}
													id={cat.id}
													name={cat.name}
													description={cat.description}
													slug={cat.slug}
													count={cat.count}
													link={cat.link}
												/>
											))}
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

export default AllCourseTags;
