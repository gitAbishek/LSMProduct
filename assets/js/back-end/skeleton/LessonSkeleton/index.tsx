import {
	Box,
	ButtonGroup,
	Container,
	Image,
	Skeleton,
	SkeletonCircle,
	SkeletonText,
	Stack,
} from '@chakra-ui/react';
import React from 'react';
import BackToBuilder from '../../components/common/BackToBuilder';
import { Logo } from '../../constants/images';

const LessonSkeleton: React.FC = () => (
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
						<SkeletonText noOfLines={1} width="80px" />
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
				<Stack direction={['column', 'column', 'column', 'row']} spacing="6">
					<Box bg="white" p="10" shadow="box" flex="1">
						<Stack direction="column" spacing="8">
							<Skeleton height="30px" width="100px" />
							<Stack mt="12px" direction="column" spacing="6">
								<Stack direction="column" spacing="3">
									<Skeleton height="40px" />
									<Skeleton height="400px" />
									<Skeleton height="40px" />
								</Stack>
								<Stack direction="row">
									<Skeleton height="40px" w="100%" />
									<Skeleton height="40px" w="100%" />
								</Stack>
							</Stack>
						</Stack>
					</Box>
					<Box bg="white" p="10" shadow="box" flex="0.5">
						<Stack direction="column" spacing="10">
							<Stack>
								<Skeleton height="10px" width="30%" />
								<Skeleton height="40px" />
							</Stack>
							<Stack>
								<Skeleton height="10px" width="30%" />
								<Skeleton height="40px" />
							</Stack>
							<Stack>
								<Skeleton height="10px" width="30%" />
								<Skeleton height="40px" />
							</Stack>
						</Stack>
					</Box>
				</Stack>
			</Stack>
		</Container>
	</Stack>
);

export default LessonSkeleton;
