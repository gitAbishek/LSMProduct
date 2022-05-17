import { Box, Button, Container, Heading, Spinner } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import queryString from 'query-string';
import React, { useEffect } from 'react';
import { useInfiniteQuery } from 'react-query';
import { useLocation } from 'react-router-dom';
import { Table, Tbody, Th, Thead, Tr } from 'react-super-responsive-table';
import EmptyInfo from '../../../../components/common/EmptyInfo';
import urls from '../../../../constants/urls';
import { CourseReviewResponse, CourseReviewSchema } from '../../../../schemas';
import { SkeletonReplyList } from '../../../../skeleton';
import API from '../../../../utils/api';
import { isEmpty } from '../../../../utils/utils';
import ReplyList from '../ReplyList';

interface Props {
	reviewId: string;
}

const AllReplies: React.FC<Props> = (props) => {
	const { reviewId } = props;
	const { search } = useLocation();
	const { page } = queryString.parse(search);

	const reviewAPI = new API(urls.reviews);

	// Scroll to replies if clicked reply count from reply field in review table
	useEffect(() => {
		if (page === 'replies')
			document
				.getElementById(`reply-table-${reviewId}`)
				?.scrollIntoView({ behavior: 'smooth' });
	}, [page, reviewId]);

	const { data, isLoading, isFetching, fetchNextPage, isSuccess, hasNextPage } =
		useInfiniteQuery<CourseReviewResponse>(
			'reviewList',
			({ pageParam = 1 }) =>
				reviewAPI.list({ parent: reviewId, per_page: 10, page: pageParam }),
			{
				getNextPageParam: ({ meta }) => {
					if (meta.pages > meta.current_page) return meta.current_page + 1;
					return false;
				},
			}
		);

	return (
		<Container maxW="container.xl" marginTop="6">
			<Box
				id={`reply-table-${reviewId}`}
				d="flex"
				flexDir="column"
				gap="6"
				bg="white"
				py="8"
				shadow="box"
				mt="6">
				<Heading as="h4" size="md" ml="3rem">
					{__('Replies', 'masteriyo')}
				</Heading>
				<Table>
					<Thead>
						<Tr>
							<Th>{__('Author', 'masteriyo')}</Th>
							<Th>{__('Content', 'masteriyo')}</Th>
							<Th>{__('Date', 'masteriyo')}</Th>
							<Th>{__('Action', 'masteriyo')}</Th>
						</Tr>
					</Thead>
					<Tbody>
						{isLoading && <SkeletonReplyList />}
						{isSuccess && isEmpty(data?.pages[0]?.data) ? (
							<EmptyInfo message={__('No replies found.', 'masteriyo')} />
						) : (
							data?.pages.map((page) =>
								page?.data.map((reply: CourseReviewSchema) => (
									<ReplyList key={reply.id} reply={reply} />
								))
							)
						)}
					</Tbody>
				</Table>
				{hasNextPage ? (
					<Button
						spinner={<Spinner />}
						spinnerPlacement="end"
						isLoading={isFetching}
						w="20%"
						m="auto"
						onClick={() => fetchNextPage()}
						variant="outline">
						Load More
					</Button>
				) : null}
			</Box>
		</Container>
	);
};

export default AllReplies;
