import {
	Box,
	Container,
	Flex,
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

import urls from '../../constants/urls';
import { SkeletonCourseTaxonomy } from '../../skeleton';
import API from '../../utils/api';
import ListRow from './components/ListRow';
import Header from './components/Header';

const AllCourseDifficulties = () => {
	const difficultiesAPI = new API(urls.difficulties);
	const difficultiesQuery = useQuery('courseDifficultiesList', () =>
		difficultiesAPI.list()
	);

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header />
			<Container maxW="container.xl">
				<Box bg="white" p="12" shadow="box" mx="auto">
					<Stack direction="column" spacing="8">
						<Flex justify="space-between" aling="center">
							<Heading as="h1" size="lg">
								{__('Difficulties', 'masteriyo')}
							</Heading>
						</Flex>

						<Table>
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
								{difficultiesQuery.isLoading && <SkeletonCourseTaxonomy />}
								{difficultiesQuery.isSuccess &&
									difficultiesQuery.data.map((cat: any) => (
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
				</Box>
			</Container>
		</Stack>
	);
};

export default AllCourseDifficulties;
