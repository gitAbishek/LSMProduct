import { Box, Container, Heading } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import queryString from 'query-string';
import React, { useEffect } from 'react';
import { useQuery } from 'react-query';
import { useHistory, useLocation } from 'react-router-dom';
import { Table, Tbody, Th, Thead, Tr } from 'react-super-responsive-table';
import EmptyInfo from '../../../../components/common/EmptyInfo';
import routes from '../../../../constants/routes';
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
	const history = useHistory();
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

	const reviewReplies = useQuery<CourseReviewResponse>(
		['reviewList'],
		() =>
			reviewAPI.list({
				parent: reviewId,
			}),
		{
			onError: () => {
				history.push(routes.notFound);
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
							<Th>{__('Created At', 'masteriyo')}</Th>
							<Th>{__('Action', 'masteriyo')}</Th>
						</Tr>
					</Thead>
					<Tbody>
						{reviewReplies.isLoading && <SkeletonReplyList />}
						{reviewReplies.isSuccess && isEmpty(reviewReplies?.data?.data) ? (
							<EmptyInfo message={__('No replies found.', 'masteriyo')} />
						) : (
							reviewReplies.data?.data?.map((reply: CourseReviewSchema) => (
								<ReplyList key={reply.id} reply={reply} />
							))
						)}
					</Tbody>
				</Table>
			</Box>
		</Container>
	);
};

export default AllReplies;
