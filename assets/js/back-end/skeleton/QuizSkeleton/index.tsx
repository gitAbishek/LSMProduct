import {
	Box,
	ButtonGroup,
	Container,
	Flex,
	Heading,
	Image,
	Skeleton,
	SkeletonCircle,
	SkeletonText,
	Stack,
} from '@chakra-ui/react';
import React from 'react';
import BackToBuilder from '../../components/common/BackToBuilder';
import { Logo } from '../../constants/images';

const QuizSKeleton: React.FC = () => {
	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Box bg="white" w="full" shadow="header" pb={['3', 0, 0]}>
				<Container maxW="container.xl">
					<Stack
						direction={['column', 'row']}
						justifyContent="space-between"
						align="center">
						<Stack
							direction={['column', null, 'row']}
							spacing={['3', null, '8']}
							align="center"
							minHeight="16">
							<Box d={['none', null, 'block']}>
								<Image src={Logo} w="36px" />
							</Box>
							<Heading>
								<SkeletonText noOfLines={1} width="80px" />
							</Heading>
							<Stack
								direction="row"
								alignItems="center"
								gap="5"
								mt="0px !important">
								<Stack direction="row" gap="3" alignItems="center">
									<SkeletonCircle size="4" />
									<SkeletonText noOfLines={1} width="40px" />
								</Stack>
								<Stack direction="row" gap="3" alignItems="center">
									<SkeletonCircle size="4" />
									<SkeletonText noOfLines={1} width="40px" />
								</Stack>
								<Stack direction="row" gap="3" alignItems="center">
									<SkeletonCircle size="4" />
									<SkeletonText noOfLines={1} width="40px" />
								</Stack>
							</Stack>
						</Stack>
						<ButtonGroup>
							<Skeleton height="40px" width="70px" />
							<Skeleton height="40px" width="70px" />
							<Skeleton height="40px" width="70px" />
						</ButtonGroup>
					</Stack>
				</Container>
			</Box>
			<Container maxW="container.xl">
				<Stack direction="column" spacing="6">
					<BackToBuilder />
					<Box bg="white" p="10" shadow="box">
						<Stack direction="column" spacing="8">
							<Flex align="center" justify="space-between">
								<Heading as="h1" fontSize="x-large">
									<Skeleton height="30px" width="100px" />
								</Heading>
							</Flex>
							<Stack mt="15px" direction="row" gap="5" justifyContent="center">
								<SkeletonText noOfLines={1} width="40px" />
								<SkeletonText noOfLines={1} width="40px" />
								<SkeletonText noOfLines={1} width="40px" />
							</Stack>
							<Stack direction="column" spacing="6">
								<Skeleton height="40px" />
								<Skeleton height="400px" />
							</Stack>
						</Stack>
					</Box>
				</Stack>
			</Container>
		</Stack>
	);
};

export default QuizSKeleton;
