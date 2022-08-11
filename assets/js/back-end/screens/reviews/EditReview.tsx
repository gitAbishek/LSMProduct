import { Stack } from '@chakra-ui/react';
import React from 'react';
import { useQuery } from 'react-query';
import { useHistory, useParams } from 'react-router-dom';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { CourseReviewSchema } from '../../schemas';
import ReviewSkeleton from '../../skeleton/ReviewSkeleton';
import API from '../../utils/api';
import AllReplies from './components/module/AllReplies';
import ReviewForm from './components/template/ReviewForm';

const EditReview: React.FC = () => {
	const { reviewId }: any = useParams();
	const history = useHistory();

	const reviewAPI = new API(urls.reviews);

	const reviewQuery = useQuery<CourseReviewSchema>(
		[`review${reviewId}`, reviewId],
		() => reviewAPI.get(reviewId),
		{
			onError: () => {
				history.push(routes.notFound);
			},
		}
	);

	if (reviewQuery.isSuccess) {
		return (
			<Stack w="100%">
				<ReviewForm editMode reviewQueryData={reviewQuery.data} />
				<AllReplies reviewId={reviewId} />
			</Stack>
		);
	}
	return <ReviewSkeleton />;
};

export default EditReview;
