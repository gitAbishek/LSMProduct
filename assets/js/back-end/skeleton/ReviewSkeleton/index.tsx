import {
	Box,
	Button,
	ButtonGroup,
	Container,
	Icon,
	Link,
	List,
	ListItem,
	Skeleton,
	SkeletonText,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiChevronLeft } from 'react-icons/bi';
import { Link as RouterLink, NavLink } from 'react-router-dom';
import Header from '../../components/common/Header';
import { navActiveStyles, navLinkStyles } from '../../config/styles';
import routes from '../../constants/routes';

const ReviewSkeleton: React.FC = () => {
	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header showLinks>
				<List>
					<ListItem>
						<Link
							as={NavLink}
							sx={navLinkStyles}
							_activeLink={navActiveStyles}
							to={routes.reviews.list}>
							{__('Update Review', 'masteriyo')}
						</Link>
					</ListItem>
				</List>
			</Header>
			<Container maxW="container.xl" marginTop="6">
				<Stack direction="column" spacing="6">
					<ButtonGroup>
						<RouterLink to={routes.reviews.list}>
							<Button
								variant="link"
								_hover={{ color: 'primary.500' }}
								leftIcon={<Icon fontSize="xl" as={BiChevronLeft} />}>
								{__('Back to Reviews', 'masteriyo')}
							</Button>
						</RouterLink>
					</ButtonGroup>
					<Stack direction="column" spacing="8">
						<Stack direction="row" spacing="8">
							<Box
								flex="1"
								bg="white"
								p="10"
								shadow="box"
								d="flex"
								flexDirection="column"
								justifyContent="space-between">
								<Stack direction="column" spacing="6">
									<Stack display="flex" direction="column" spacing="4">
										<SkeletonText noOfLines={1} width="40px" />
										<Skeleton height="40px" />
									</Stack>
									<Stack display="flex" direction="column" spacing="4">
										<SkeletonText noOfLines={1} width="40px" />
										<Skeleton height="100px" />
									</Stack>
									<Stack display="flex" direction="row" spacing="6">
										<Stack display="flex" direction="column" flex="50%">
											<SkeletonText noOfLines={1} width="40px" />
											<Skeleton height="40px" />
										</Stack>
										<Stack display="flex" direction="column" flex="50%">
											<SkeletonText noOfLines={1} width="40px" />
											<Skeleton height="40px" />
										</Stack>
									</Stack>
								</Stack>
							</Box>
						</Stack>
					</Stack>
				</Stack>
			</Container>
		</Stack>
	);
};

export default ReviewSkeleton;
