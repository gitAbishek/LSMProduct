import {
	Box,
	Button,
	ButtonGroup,
	Flex,
	FormControl,
	FormLabel,
	Link,
	Select,
	Stack,
	Text,
	Skeleton,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useQuery } from 'react-query';
import { useFormContext } from 'react-hook-form';
import PagesAPI from '../../../back-end/utils/pages';
interface Props {
	mutationLoading: boolean;
	dashboardURL: string;
	prevStep: () => void;
}

const Pages: React.FC<Props> = (props) => {
	const { mutationLoading, dashboardURL, prevStep } = props;
	const { register } = useFormContext();
	const pageAPI = new PagesAPI();
	const pagesQuery = useQuery('pages', () => pageAPI.list());

	const renderPagesOption = () => {
		try {
			return (
				<>
					<option value="">{__('Select a page...', 'masteriyo')}</option>
					{pagesQuery?.data?.map(
						(page: { id: number; title: { rendered: string } }) => (
							<option value={page.id} key={page.id}>
								{page.title.rendered}
							</option>
						)
					)}
				</>
			);
		} catch (error) {
			console.error(error);
			return;
		}
	};

	return (
		<Box rounded="3px">
			<Box bg="white" p="30" shadow="box">
				<Stack direction="column" spacing="8">
					{pagesQuery.isLoading ? (
						<>
							<Stack spacing="8">
								<Skeleton h="6" />
								<Skeleton h="6" />
								<Skeleton h="6" />
							</Stack>
						</>
					) : (
						<>
							<FormControl>
								<Flex justify="space-between" align="center">
									<FormLabel sx={{ fontWeight: 'bold' }}>
										{__('Course List', 'masteriyo')}
									</FormLabel>
									<Select w="md" {...register('pages.course_list_page_id')}>
										{renderPagesOption()}
									</Select>
								</Flex>
							</FormControl>

							<FormControl>
								<Flex justify="space-between" align="center">
									<FormLabel sx={{ fontWeight: 'bold' }}>
										<Text fontSize="sm">{__('My Account', 'masteriyo')}</Text>
									</FormLabel>
									<Select w="md" {...register('pages.myaccount_page_id')}>
										{renderPagesOption()}
									</Select>
								</Flex>
							</FormControl>

							<FormControl>
								<Flex justify="space-between" align="center">
									<FormLabel sx={{ fontWeight: 'bold' }}>
										{__('Checkout', 'masteriyo')}
									</FormLabel>
									<Select w="md" {...register('pages.checkout_page_id')}>
										{renderPagesOption()}
									</Select>
								</Flex>
							</FormControl>

							<Flex justify="space-between" align="center">
								<Button
									onClick={prevStep}
									rounded="3px"
									colorScheme="blue"
									variant="outline">
									{__('Back', 'masteriyo')}
								</Button>
								<ButtonGroup>
									<Link href={dashboardURL ? dashboardURL : '#'}>
										<Button variant="ghost">
											{__('Skip to Dashboard', 'masteriyo')}
										</Button>
									</Link>
									<Button
										type="submit"
										isLoading={mutationLoading}
										rounded="3px"
										colorScheme="blue">
										{__('Finish', 'masteriyo')}
									</Button>
								</ButtonGroup>
							</Flex>
						</>
					)}
				</Stack>
			</Box>
		</Box>
	);
};

export default Pages;
